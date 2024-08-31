import fitz #PyMuPDF
import PIL.Image #pillow
import io
import cv2
from datetime import date 
import os
from flask_cors import CORS
from flask import Flask, request, jsonify

# Get the filename without extension from path provided by the user
def pdf_converter(filepath):
    file_nm = os.path.split(filepath)[1].split('.')[0]
    print(file_nm)

    # To get the today's date
    today = date.today()                       
    Date = (today)

    #List to save names of output image
    result_img = []

    master_dict = {'Ladle 9':
                {"Wall Contour":[2,50,931,373],
                "Bottom Contour": [2,52,873,915],
                "Round Image":[1,34,898,861],
                "Radial Section@45":[6,52,935,916],
                "Radial Section@135":[3,44,950,917],
                "Z Section":[3,51,876,915],
                "V image":[73,62,824,647],
                "Intensity Image":[3,45,1091,912],
                "Reference Diagnostics":[213,289,734,725]},
                'Ladle 11': 
                {"Wall Contour":[3,51,942,357],
                "Bottom Contour": [9,48,869,916],
                "Round Image":[37,1,865,507],
                "Radial Section@45":[3,52,935,915],
                "Radial Section@135":[3,47,945,916],
                "Z Section":[2,50,871,916],
                "V image":[122,1,665,309],
                "Intensity Image":[4,49,1080,913],
                "Reference Diagnostics":[216,289,652,732]},
                'Ladle 15':
                {"Wall Contour":[2,52,940,356],
                "Bottom Contour": [23,47,870,897],
                "Round Image":[3,50,888,821],
                "Radial Section@45":[3,53,935,915],
                "Radial Section@135":[1,48,947,914],
                "Z Section":[2,48,875,913],
                "V image":[32,63,853,570],
                "Intensity Image":[4,48,1089,911],
                "Reference Diagnostics":[230,307,635,710]},
                'Ladle 20':
                {"Wall Contour":[2,48,942,366],
                "Bottom Contour": [4,46,866,915],
                "Round Image":[47,211,883,803],
                "Radial Section@45":[3,49,932,915],
                "Radial Section@135":[1,49,946,915],
                "Z Section":[3,48,871,916],
                "V image":[249,219,771,740],
                "Intensity Image":[3,50,1077,914],
                "Reference Diagnostics":[235,314,638,715]}
                }

    # Choosing the template dictionary relevant for the input file
    if file_nm in list(master_dict.keys()):
        name_px_dict = master_dict[file_nm]
    else:
        name_px_dict = master_dict['Ladle 9']


    # Reading the PDF file
    pdf = fitz.open(filepath) 

    #Initialize the counter 
    counter = 0

    for i in range(len(pdf)):
        #print(len(pdf))
        page = pdf[i]
        images = page.get_images()

        # Getting Top/Bottom X,Y coordinates and the Image name from the dictionary
        top_left_x =list(name_px_dict.items())[counter][1][0]
        top_left_y =list(name_px_dict.items())[counter][1][1]
        bottom_right_x = list(name_px_dict.items())[counter][1][2]
        bottom_right_y = list(name_px_dict.items())[counter][1][3]

    # Reading image from each page of PDF
        for image in images:
            
            base_img = pdf.extract_image(image[0])
            image_data = base_img["image"]
            img = PIL.Image.open(io.BytesIO(image_data))
            extension = base_img['ext']
            #print(counter)
            #print(extension)


            cropped_image_name = file_nm + '_' + list(name_px_dict.keys())[counter] + '_' + str(Date) + "." + extension
            result_img.append(cropped_image_name)

            #print(cropped_image_name)
            img.save(open(os.path.split(filepath)[0]+ "\\"+ f"image{counter}.{extension}", "wb"))

            dir_path = os.path.split(filepath)[0]
            
            img_nm = "image" + str(counter) + "." + extension
            
            img_path = dir_path + '\\' +  img_nm
            
            ext_img = cv2.imread(img_path)

            crop_img = ext_img[top_left_y:bottom_right_y, top_left_x:bottom_right_x]

            #Delete the PDF page image
            os.remove(img_path)

            
            cropped_img_path = os.path.split(filepath)[0]

            #print(cropped_img_path)


            dest_path = cropped_img_path + '\\' + cropped_image_name

            #print(dest_path)

            cv2.imwrite(dest_path,crop_img)
            counter += 1

    print("\n\n*******IMAGE FILES CREATED ARE LISTED BELOW******* \n")

    # for i in result_img:
    #     print(i)
    
    return result_img

# filepath = input("Enter the filepath: ")
# print(filepath)
# pdf_converter(filepath)


app = Flask(__name__)
CORS(app)

@app.route('/runCampain', methods=['POST'])
def runCampain():
    data = request.get_json()
    pdf_file = data.get('pdf_file')
    print('pdf_file:----', pdf_file)
    result = pdf_converter(pdf_file)
    send_result = []
    for i in result:
        spl = i.split('_')
        send_result.append({'name': spl[0], 'location': spl[1], 'date': spl[2].split('.')[0], 'file': i})
    
    return jsonify({'status': 'success', 'message': 'Files Generated Successfully', 'result': send_result }), 200

# Run the Flask application
if __name__ == '__main__':
    app.run(debug=True)