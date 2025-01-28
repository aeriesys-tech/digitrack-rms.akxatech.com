--
-- PostgreSQL database dump
--

-- Dumped from database version 17.2
-- Dumped by pg_dump version 17.2

-- Started on 2025-01-28 22:59:19 IST

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 4 (class 2615 OID 2200)
-- Name: public; Type: SCHEMA; Schema: -; Owner: pg_database_owner
--

CREATE SCHEMA public;


ALTER SCHEMA public OWNER TO pg_database_owner;

--
-- TOC entry 4887 (class 0 OID 0)
-- Dependencies: 4
-- Name: SCHEMA public; Type: COMMENT; Schema: -; Owner: pg_database_owner
--

COMMENT ON SCHEMA public IS 'standard public schema';


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 260 (class 1259 OID 39303)
-- Name: abilities; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.abilities (
    ability_id bigint NOT NULL,
    ability character varying(255) NOT NULL,
    description character varying(255),
    module_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.abilities OWNER TO postgres;

--
-- TOC entry 259 (class 1259 OID 39302)
-- Name: abilities_ability_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.abilities_ability_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.abilities_ability_id_seq OWNER TO postgres;

--
-- TOC entry 4888 (class 0 OID 0)
-- Dependencies: 259
-- Name: abilities_ability_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.abilities_ability_id_seq OWNED BY public.abilities.ability_id;


--
-- TOC entry 312 (class 1259 OID 39765)
-- Name: accessory_types; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.accessory_types (
    accessory_type_id bigint NOT NULL,
    accessory_type_code character varying(255) NOT NULL,
    accessory_type_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.accessory_types OWNER TO postgres;

--
-- TOC entry 311 (class 1259 OID 39764)
-- Name: accessory_types_accessory_type_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.accessory_types_accessory_type_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.accessory_types_accessory_type_id_seq OWNER TO postgres;

--
-- TOC entry 4889 (class 0 OID 0)
-- Dependencies: 311
-- Name: accessory_types_accessory_type_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.accessory_types_accessory_type_id_seq OWNED BY public.accessory_types.accessory_type_id;


--
-- TOC entry 376 (class 1259 OID 40463)
-- Name: activity_attribute_types; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.activity_attribute_types (
    activity_attribute_type_id bigint NOT NULL,
    activity_attribute_id bigint NOT NULL,
    reason_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.activity_attribute_types OWNER TO postgres;

--
-- TOC entry 375 (class 1259 OID 40462)
-- Name: activity_attribute_types_activity_attribute_type_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.activity_attribute_types_activity_attribute_type_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.activity_attribute_types_activity_attribute_type_id_seq OWNER TO postgres;

--
-- TOC entry 4890 (class 0 OID 0)
-- Dependencies: 375
-- Name: activity_attribute_types_activity_attribute_type_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.activity_attribute_types_activity_attribute_type_id_seq OWNED BY public.activity_attribute_types.activity_attribute_type_id;


--
-- TOC entry 380 (class 1259 OID 40497)
-- Name: activity_attribute_values; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.activity_attribute_values (
    activity_attribute_value_id bigint NOT NULL,
    user_activity_id bigint NOT NULL,
    activity_attribute_id bigint NOT NULL,
    field_value character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.activity_attribute_values OWNER TO postgres;

--
-- TOC entry 379 (class 1259 OID 40496)
-- Name: activity_attribute_values_activity_attribute_value_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.activity_attribute_values_activity_attribute_value_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.activity_attribute_values_activity_attribute_value_id_seq OWNER TO postgres;

--
-- TOC entry 4891 (class 0 OID 0)
-- Dependencies: 379
-- Name: activity_attribute_values_activity_attribute_value_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.activity_attribute_values_activity_attribute_value_id_seq OWNED BY public.activity_attribute_values.activity_attribute_value_id;


--
-- TOC entry 374 (class 1259 OID 40443)
-- Name: activity_attributes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.activity_attributes (
    activity_attribute_id bigint NOT NULL,
    field_name character varying(100) NOT NULL,
    display_name character varying(100) NOT NULL,
    field_type character varying(50) NOT NULL,
    field_values text,
    field_length integer NOT NULL,
    is_required boolean DEFAULT false NOT NULL,
    user_id bigint NOT NULL,
    list_parameter_id bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.activity_attributes OWNER TO postgres;

--
-- TOC entry 373 (class 1259 OID 40442)
-- Name: activity_attributes_activity_attribute_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.activity_attributes_activity_attribute_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.activity_attributes_activity_attribute_id_seq OWNER TO postgres;

--
-- TOC entry 4892 (class 0 OID 0)
-- Dependencies: 373
-- Name: activity_attributes_activity_attribute_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.activity_attributes_activity_attribute_id_seq OWNED BY public.activity_attributes.activity_attribute_id;


--
-- TOC entry 227 (class 1259 OID 39142)
-- Name: areas; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.areas (
    area_id bigint NOT NULL,
    area_code character varying(100) NOT NULL,
    area_name character varying(100) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.areas OWNER TO postgres;

--
-- TOC entry 226 (class 1259 OID 39141)
-- Name: areas_area_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.areas_area_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.areas_area_id_seq OWNER TO postgres;

--
-- TOC entry 4893 (class 0 OID 0)
-- Dependencies: 226
-- Name: areas_area_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.areas_area_id_seq OWNED BY public.areas.area_id;


--
-- TOC entry 368 (class 1259 OID 40319)
-- Name: asset_accessories; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.asset_accessories (
    asset_accessory_id bigint NOT NULL,
    area_id bigint NOT NULL,
    plant_id bigint NOT NULL,
    asset_id bigint NOT NULL,
    asset_zone_id bigint,
    accessory_type_id bigint NOT NULL,
    accessory_name character varying(100) NOT NULL,
    attachment character varying(100) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.asset_accessories OWNER TO postgres;

--
-- TOC entry 367 (class 1259 OID 40318)
-- Name: asset_accessories_asset_accessory_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.asset_accessories_asset_accessory_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.asset_accessories_asset_accessory_id_seq OWNER TO postgres;

--
-- TOC entry 4894 (class 0 OID 0)
-- Dependencies: 367
-- Name: asset_accessories_asset_accessory_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.asset_accessories_asset_accessory_id_seq OWNED BY public.asset_accessories.asset_accessory_id;


--
-- TOC entry 294 (class 1259 OID 39608)
-- Name: asset_attribute_types; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.asset_attribute_types (
    asset_attribute_type_id bigint NOT NULL,
    asset_attribute_id bigint NOT NULL,
    asset_type_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.asset_attribute_types OWNER TO postgres;

--
-- TOC entry 293 (class 1259 OID 39607)
-- Name: asset_attribute_types_asset_attribute_type_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.asset_attribute_types_asset_attribute_type_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.asset_attribute_types_asset_attribute_type_id_seq OWNER TO postgres;

--
-- TOC entry 4895 (class 0 OID 0)
-- Dependencies: 293
-- Name: asset_attribute_types_asset_attribute_type_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.asset_attribute_types_asset_attribute_type_id_seq OWNED BY public.asset_attribute_types.asset_attribute_type_id;


--
-- TOC entry 296 (class 1259 OID 39625)
-- Name: asset_attribute_values; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.asset_attribute_values (
    asset_attribute_value_id bigint NOT NULL,
    asset_id bigint NOT NULL,
    asset_attribute_id bigint NOT NULL,
    field_value character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.asset_attribute_values OWNER TO postgres;

--
-- TOC entry 295 (class 1259 OID 39624)
-- Name: asset_attribute_values_asset_attribute_value_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.asset_attribute_values_asset_attribute_value_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.asset_attribute_values_asset_attribute_value_id_seq OWNER TO postgres;

--
-- TOC entry 4896 (class 0 OID 0)
-- Dependencies: 295
-- Name: asset_attribute_values_asset_attribute_value_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.asset_attribute_values_asset_attribute_value_id_seq OWNED BY public.asset_attribute_values.asset_attribute_value_id;


--
-- TOC entry 292 (class 1259 OID 39593)
-- Name: asset_attributes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.asset_attributes (
    asset_attribute_id bigint NOT NULL,
    field_name character varying(100) NOT NULL,
    display_name character varying(100) NOT NULL,
    field_type character varying(50) NOT NULL,
    field_values text,
    field_length integer NOT NULL,
    is_required boolean DEFAULT false NOT NULL,
    user_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    list_parameter_id bigint
);


ALTER TABLE public.asset_attributes OWNER TO postgres;

--
-- TOC entry 291 (class 1259 OID 39592)
-- Name: asset_attributes_asset_attribute_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.asset_attributes_asset_attribute_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.asset_attributes_asset_attribute_id_seq OWNER TO postgres;

--
-- TOC entry 4897 (class 0 OID 0)
-- Dependencies: 291
-- Name: asset_attributes_asset_attribute_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.asset_attributes_asset_attribute_id_seq OWNED BY public.asset_attributes.asset_attribute_id;


--
-- TOC entry 274 (class 1259 OID 39411)
-- Name: asset_checks; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.asset_checks (
    asset_check_id bigint NOT NULL,
    check_id bigint NOT NULL,
    asset_id bigint NOT NULL,
    plant_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    lcl double precision,
    ucl double precision,
    default_value character varying(100),
    area_id bigint,
    asset_zone_id bigint
);


ALTER TABLE public.asset_checks OWNER TO postgres;

--
-- TOC entry 273 (class 1259 OID 39410)
-- Name: asset_checks_asset_check_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.asset_checks_asset_check_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.asset_checks_asset_check_id_seq OWNER TO postgres;

--
-- TOC entry 4898 (class 0 OID 0)
-- Dependencies: 273
-- Name: asset_checks_asset_check_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.asset_checks_asset_check_id_seq OWNED BY public.asset_checks.asset_check_id;


--
-- TOC entry 388 (class 1259 OID 40615)
-- Name: asset_data_source_values; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.asset_data_source_values (
    asset_data_source_value_id bigint NOT NULL,
    asset_data_source_id bigint NOT NULL,
    asset_id bigint NOT NULL,
    data_source_id bigint NOT NULL,
    asset_zone_id bigint NOT NULL,
    data_source_attribute_id bigint NOT NULL,
    field_value character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.asset_data_source_values OWNER TO postgres;

--
-- TOC entry 387 (class 1259 OID 40614)
-- Name: asset_data_source_values_asset_data_source_value_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.asset_data_source_values_asset_data_source_value_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.asset_data_source_values_asset_data_source_value_id_seq OWNER TO postgres;

--
-- TOC entry 4899 (class 0 OID 0)
-- Dependencies: 387
-- Name: asset_data_source_values_asset_data_source_value_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.asset_data_source_values_asset_data_source_value_id_seq OWNED BY public.asset_data_source_values.asset_data_source_value_id;


--
-- TOC entry 366 (class 1259 OID 40276)
-- Name: asset_data_sources; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.asset_data_sources (
    asset_data_source_id bigint NOT NULL,
    area_id bigint NOT NULL,
    plant_id bigint NOT NULL,
    asset_id bigint NOT NULL,
    asset_zone_id bigint,
    data_source_type_id bigint NOT NULL,
    data_source_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    script character varying(255) NOT NULL
);


ALTER TABLE public.asset_data_sources OWNER TO postgres;

--
-- TOC entry 365 (class 1259 OID 40275)
-- Name: asset_data_sources_asset_data_source_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.asset_data_sources_asset_data_source_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.asset_data_sources_asset_data_source_id_seq OWNER TO postgres;

--
-- TOC entry 4900 (class 0 OID 0)
-- Dependencies: 365
-- Name: asset_data_sources_asset_data_source_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.asset_data_sources_asset_data_source_id_seq OWNED BY public.asset_data_sources.asset_data_source_id;


--
-- TOC entry 378 (class 1259 OID 40480)
-- Name: asset_departments; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.asset_departments (
    asset_department_id bigint NOT NULL,
    asset_id bigint NOT NULL,
    department_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.asset_departments OWNER TO postgres;

--
-- TOC entry 377 (class 1259 OID 40479)
-- Name: asset_departments_asset_department_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.asset_departments_asset_department_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.asset_departments_asset_department_id_seq OWNER TO postgres;

--
-- TOC entry 4901 (class 0 OID 0)
-- Dependencies: 377
-- Name: asset_departments_asset_department_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.asset_departments_asset_department_id_seq OWNED BY public.asset_departments.asset_department_id;


--
-- TOC entry 384 (class 1259 OID 40551)
-- Name: asset_service_values; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.asset_service_values (
    asset_service_value_id bigint NOT NULL,
    asset_service_id bigint NOT NULL,
    asset_id bigint NOT NULL,
    service_id bigint NOT NULL,
    asset_zone_id bigint NOT NULL,
    service_attribute_id bigint NOT NULL,
    field_value character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.asset_service_values OWNER TO postgres;

--
-- TOC entry 383 (class 1259 OID 40550)
-- Name: asset_service_values_asset_service_value_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.asset_service_values_asset_service_value_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.asset_service_values_asset_service_value_id_seq OWNER TO postgres;

--
-- TOC entry 4902 (class 0 OID 0)
-- Dependencies: 383
-- Name: asset_service_values_asset_service_value_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.asset_service_values_asset_service_value_id_seq OWNED BY public.asset_service_values.asset_service_value_id;


--
-- TOC entry 304 (class 1259 OID 39693)
-- Name: asset_services; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.asset_services (
    asset_service_id bigint NOT NULL,
    service_id bigint NOT NULL,
    asset_id bigint NOT NULL,
    plant_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    area_id bigint,
    asset_zone_id bigint,
    service_type_id bigint
);


ALTER TABLE public.asset_services OWNER TO postgres;

--
-- TOC entry 303 (class 1259 OID 39692)
-- Name: asset_services_asset_service_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.asset_services_asset_service_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.asset_services_asset_service_id_seq OWNER TO postgres;

--
-- TOC entry 4903 (class 0 OID 0)
-- Dependencies: 303
-- Name: asset_services_asset_service_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.asset_services_asset_service_id_seq OWNED BY public.asset_services.asset_service_id;


--
-- TOC entry 382 (class 1259 OID 40519)
-- Name: asset_spare_values; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.asset_spare_values (
    asset_spare_value_id bigint NOT NULL,
    asset_spare_id bigint NOT NULL,
    asset_id bigint NOT NULL,
    spare_id bigint NOT NULL,
    asset_zone_id bigint NOT NULL,
    spare_attribute_id bigint NOT NULL,
    field_value character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.asset_spare_values OWNER TO postgres;

--
-- TOC entry 381 (class 1259 OID 40518)
-- Name: asset_spare_values_asset_spare_value_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.asset_spare_values_asset_spare_value_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.asset_spare_values_asset_spare_value_id_seq OWNER TO postgres;

--
-- TOC entry 4904 (class 0 OID 0)
-- Dependencies: 381
-- Name: asset_spare_values_asset_spare_value_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.asset_spare_values_asset_spare_value_id_seq OWNED BY public.asset_spare_values.asset_spare_value_id;


--
-- TOC entry 272 (class 1259 OID 39389)
-- Name: asset_spares; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.asset_spares (
    asset_spare_id bigint NOT NULL,
    spare_id bigint NOT NULL,
    asset_id bigint NOT NULL,
    plant_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    area_id bigint,
    asset_zone_id bigint,
    spare_type_id bigint,
    quantity integer
);


ALTER TABLE public.asset_spares OWNER TO postgres;

--
-- TOC entry 271 (class 1259 OID 39388)
-- Name: asset_spares_asset_spare_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.asset_spares_asset_spare_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.asset_spares_asset_spare_id_seq OWNER TO postgres;

--
-- TOC entry 4905 (class 0 OID 0)
-- Dependencies: 271
-- Name: asset_spares_asset_spare_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.asset_spares_asset_spare_id_seq OWNED BY public.asset_spares.asset_spare_id;


--
-- TOC entry 416 (class 1259 OID 41041)
-- Name: asset_template_accessories; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.asset_template_accessories (
    asset_template_accessory_id bigint NOT NULL,
    area_id bigint NOT NULL,
    plant_id bigint NOT NULL,
    asset_template_id bigint NOT NULL,
    template_zone_id bigint,
    accessory_type_id bigint NOT NULL,
    accessory_name character varying(100) NOT NULL,
    attachment character varying(100) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.asset_template_accessories OWNER TO postgres;

--
-- TOC entry 415 (class 1259 OID 41040)
-- Name: asset_template_accessories_asset_template_accessory_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.asset_template_accessories_asset_template_accessory_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.asset_template_accessories_asset_template_accessory_id_seq OWNER TO postgres;

--
-- TOC entry 4906 (class 0 OID 0)
-- Dependencies: 415
-- Name: asset_template_accessories_asset_template_accessory_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.asset_template_accessories_asset_template_accessory_id_seq OWNED BY public.asset_template_accessories.asset_template_accessory_id;


--
-- TOC entry 402 (class 1259 OID 40802)
-- Name: asset_template_checks; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.asset_template_checks (
    asset_template_check_id bigint NOT NULL,
    area_id bigint,
    check_id bigint NOT NULL,
    asset_template_id bigint NOT NULL,
    template_zone_id bigint,
    plant_id bigint NOT NULL,
    lcl double precision,
    ucl double precision,
    default_value character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.asset_template_checks OWNER TO postgres;

--
-- TOC entry 401 (class 1259 OID 40801)
-- Name: asset_template_checks_asset_template_check_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.asset_template_checks_asset_template_check_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.asset_template_checks_asset_template_check_id_seq OWNER TO postgres;

--
-- TOC entry 4907 (class 0 OID 0)
-- Dependencies: 401
-- Name: asset_template_checks_asset_template_check_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.asset_template_checks_asset_template_check_id_seq OWNED BY public.asset_template_checks.asset_template_check_id;


--
-- TOC entry 412 (class 1259 OID 40972)
-- Name: asset_template_datasources; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.asset_template_datasources (
    asset_template_datasource_id bigint NOT NULL,
    area_id bigint NOT NULL,
    plant_id bigint NOT NULL,
    asset_template_id bigint NOT NULL,
    template_zone_id bigint,
    data_source_type_id bigint NOT NULL,
    data_source_id bigint NOT NULL,
    script character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.asset_template_datasources OWNER TO postgres;

--
-- TOC entry 411 (class 1259 OID 40971)
-- Name: asset_template_datasources_asset_template_datasource_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.asset_template_datasources_asset_template_datasource_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.asset_template_datasources_asset_template_datasource_id_seq OWNER TO postgres;

--
-- TOC entry 4908 (class 0 OID 0)
-- Dependencies: 411
-- Name: asset_template_datasources_asset_template_datasource_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.asset_template_datasources_asset_template_datasource_id_seq OWNED BY public.asset_template_datasources.asset_template_datasource_id;


--
-- TOC entry 404 (class 1259 OID 40834)
-- Name: asset_template_services; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.asset_template_services (
    asset_template_service_id bigint NOT NULL,
    area_id bigint,
    service_id bigint NOT NULL,
    asset_template_id bigint NOT NULL,
    template_zone_id bigint,
    plant_id bigint NOT NULL,
    service_type_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.asset_template_services OWNER TO postgres;

--
-- TOC entry 403 (class 1259 OID 40833)
-- Name: asset_template_services_asset_template_service_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.asset_template_services_asset_template_service_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.asset_template_services_asset_template_service_id_seq OWNER TO postgres;

--
-- TOC entry 4909 (class 0 OID 0)
-- Dependencies: 403
-- Name: asset_template_services_asset_template_service_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.asset_template_services_asset_template_service_id_seq OWNED BY public.asset_template_services.asset_template_service_id;


--
-- TOC entry 398 (class 1259 OID 40733)
-- Name: asset_template_spares; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.asset_template_spares (
    asset_template_spare_id bigint NOT NULL,
    spare_id bigint NOT NULL,
    asset_template_id bigint NOT NULL,
    plant_id bigint NOT NULL,
    area_id bigint NOT NULL,
    template_zone_id bigint NOT NULL,
    spare_type_id bigint NOT NULL,
    quantity integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.asset_template_spares OWNER TO postgres;

--
-- TOC entry 397 (class 1259 OID 40732)
-- Name: asset_template_spares_asset_template_spare_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.asset_template_spares_asset_template_spare_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.asset_template_spares_asset_template_spare_id_seq OWNER TO postgres;

--
-- TOC entry 4910 (class 0 OID 0)
-- Dependencies: 397
-- Name: asset_template_spares_asset_template_spare_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.asset_template_spares_asset_template_spare_id_seq OWNED BY public.asset_template_spares.asset_template_spare_id;


--
-- TOC entry 408 (class 1259 OID 40903)
-- Name: asset_template_variables; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.asset_template_variables (
    asset_template_variable_id bigint NOT NULL,
    area_id bigint NOT NULL,
    plant_id bigint NOT NULL,
    asset_template_id bigint NOT NULL,
    template_zone_id bigint,
    variable_type_id bigint NOT NULL,
    variable_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.asset_template_variables OWNER TO postgres;

--
-- TOC entry 407 (class 1259 OID 40902)
-- Name: asset_template_variables_asset_template_variable_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.asset_template_variables_asset_template_variable_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.asset_template_variables_asset_template_variable_id_seq OWNER TO postgres;

--
-- TOC entry 4911 (class 0 OID 0)
-- Dependencies: 407
-- Name: asset_template_variables_asset_template_variable_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.asset_template_variables_asset_template_variable_id_seq OWNED BY public.asset_template_variables.asset_template_variable_id;


--
-- TOC entry 390 (class 1259 OID 40652)
-- Name: asset_templates; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.asset_templates (
    asset_template_id bigint NOT NULL,
    template_code character varying(100) NOT NULL,
    template_name character varying(100) NOT NULL,
    asset_type_id bigint NOT NULL,
    latitude character varying(255),
    longitude character varying(255),
    radius character varying(255),
    plant_id bigint NOT NULL,
    section_id bigint,
    area_id bigint,
    no_of_zones integer NOT NULL,
    functional_id bigint,
    geometry_type character varying(255),
    height numeric(8,2),
    diameter numeric(8,2),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.asset_templates OWNER TO postgres;

--
-- TOC entry 389 (class 1259 OID 40651)
-- Name: asset_templates_asset_template_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.asset_templates_asset_template_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.asset_templates_asset_template_id_seq OWNER TO postgres;

--
-- TOC entry 4912 (class 0 OID 0)
-- Dependencies: 389
-- Name: asset_templates_asset_template_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.asset_templates_asset_template_id_seq OWNED BY public.asset_templates.asset_template_id;


--
-- TOC entry 248 (class 1259 OID 39251)
-- Name: asset_type; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.asset_type (
    asset_type_id bigint NOT NULL,
    asset_type_code character varying(100) NOT NULL,
    asset_type_name character varying(100) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.asset_type OWNER TO postgres;

--
-- TOC entry 247 (class 1259 OID 39250)
-- Name: asset_type_asset_type_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.asset_type_asset_type_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.asset_type_asset_type_id_seq OWNER TO postgres;

--
-- TOC entry 4913 (class 0 OID 0)
-- Dependencies: 247
-- Name: asset_type_asset_type_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.asset_type_asset_type_id_seq OWNED BY public.asset_type.asset_type_id;


--
-- TOC entry 386 (class 1259 OID 40583)
-- Name: asset_variable_values; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.asset_variable_values (
    asset_variable_value_id bigint NOT NULL,
    asset_variable_id bigint NOT NULL,
    asset_id bigint NOT NULL,
    variable_id bigint NOT NULL,
    asset_zone_id bigint NOT NULL,
    variable_attribute_id bigint NOT NULL,
    field_value character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.asset_variable_values OWNER TO postgres;

--
-- TOC entry 385 (class 1259 OID 40582)
-- Name: asset_variable_values_asset_variable_value_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.asset_variable_values_asset_variable_value_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.asset_variable_values_asset_variable_value_id_seq OWNER TO postgres;

--
-- TOC entry 4914 (class 0 OID 0)
-- Dependencies: 385
-- Name: asset_variable_values_asset_variable_value_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.asset_variable_values_asset_variable_value_id_seq OWNED BY public.asset_variable_values.asset_variable_value_id;


--
-- TOC entry 364 (class 1259 OID 40233)
-- Name: asset_variables; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.asset_variables (
    asset_variable_id bigint NOT NULL,
    area_id bigint NOT NULL,
    plant_id bigint NOT NULL,
    asset_id bigint NOT NULL,
    asset_zone_id bigint,
    variable_type_id bigint NOT NULL,
    variable_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.asset_variables OWNER TO postgres;

--
-- TOC entry 363 (class 1259 OID 40232)
-- Name: asset_variables_asset_variable_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.asset_variables_asset_variable_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.asset_variables_asset_variable_id_seq OWNER TO postgres;

--
-- TOC entry 4915 (class 0 OID 0)
-- Dependencies: 363
-- Name: asset_variables_asset_variable_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.asset_variables_asset_variable_id_seq OWNED BY public.asset_variables.asset_variable_id;


--
-- TOC entry 362 (class 1259 OID 40180)
-- Name: asset_zones; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.asset_zones (
    asset_zone_id bigint NOT NULL,
    asset_id bigint NOT NULL,
    zone_name character varying(100) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    height numeric(8,2),
    diameter numeric(8,2)
);


ALTER TABLE public.asset_zones OWNER TO postgres;

--
-- TOC entry 361 (class 1259 OID 40179)
-- Name: asset_zones_asset_zone_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.asset_zones_asset_zone_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.asset_zones_asset_zone_id_seq OWNER TO postgres;

--
-- TOC entry 4916 (class 0 OID 0)
-- Dependencies: 361
-- Name: asset_zones_asset_zone_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.asset_zones_asset_zone_id_seq OWNED BY public.asset_zones.asset_zone_id;


--
-- TOC entry 270 (class 1259 OID 39372)
-- Name: assets; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.assets (
    asset_id bigint NOT NULL,
    plant_id bigint NOT NULL,
    asset_code character varying(100) NOT NULL,
    asset_name character varying(100) NOT NULL,
    asset_type_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    latitude character varying(255),
    longitude character varying(255),
    section_id bigint,
    radius character varying(255),
    area_id bigint,
    no_of_zones integer NOT NULL,
    functional_id bigint,
    geometry_type character varying(255),
    height numeric(8,2),
    diameter numeric(8,2),
    asset_template_id bigint
);


ALTER TABLE public.assets OWNER TO postgres;

--
-- TOC entry 269 (class 1259 OID 39371)
-- Name: assets_asset_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.assets_asset_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.assets_asset_id_seq OWNER TO postgres;

--
-- TOC entry 4917 (class 0 OID 0)
-- Dependencies: 269
-- Name: assets_asset_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.assets_asset_id_seq OWNED BY public.assets.asset_id;


--
-- TOC entry 334 (class 1259 OID 39926)
-- Name: break_down_attribute_types; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.break_down_attribute_types (
    break_down_attribute_type_id bigint NOT NULL,
    break_down_attribute_id bigint NOT NULL,
    break_down_type_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.break_down_attribute_types OWNER TO postgres;

--
-- TOC entry 333 (class 1259 OID 39925)
-- Name: break_down_attribute_types_break_down_attribute_type_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.break_down_attribute_types_break_down_attribute_type_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.break_down_attribute_types_break_down_attribute_type_id_seq OWNER TO postgres;

--
-- TOC entry 4918 (class 0 OID 0)
-- Dependencies: 333
-- Name: break_down_attribute_types_break_down_attribute_type_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.break_down_attribute_types_break_down_attribute_type_id_seq OWNED BY public.break_down_attribute_types.break_down_attribute_type_id;


--
-- TOC entry 352 (class 1259 OID 40064)
-- Name: break_down_attribute_values; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.break_down_attribute_values (
    break_down_attribute_value_id bigint NOT NULL,
    break_down_list_id bigint NOT NULL,
    break_down_attribute_id bigint NOT NULL,
    field_value character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.break_down_attribute_values OWNER TO postgres;

--
-- TOC entry 351 (class 1259 OID 40063)
-- Name: break_down_attribute_values_break_down_attribute_value_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.break_down_attribute_values_break_down_attribute_value_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.break_down_attribute_values_break_down_attribute_value_id_seq OWNER TO postgres;

--
-- TOC entry 4919 (class 0 OID 0)
-- Dependencies: 351
-- Name: break_down_attribute_values_break_down_attribute_value_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.break_down_attribute_values_break_down_attribute_value_id_seq OWNED BY public.break_down_attribute_values.break_down_attribute_value_id;


--
-- TOC entry 324 (class 1259 OID 39843)
-- Name: break_down_attributes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.break_down_attributes (
    break_down_attribute_id bigint NOT NULL,
    field_name character varying(100) NOT NULL,
    display_name character varying(100) NOT NULL,
    field_type character varying(50) NOT NULL,
    field_values text,
    field_length integer NOT NULL,
    is_required boolean DEFAULT false NOT NULL,
    user_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    list_parameter_id bigint
);


ALTER TABLE public.break_down_attributes OWNER TO postgres;

--
-- TOC entry 323 (class 1259 OID 39842)
-- Name: break_down_attributes_break_down_attribute_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.break_down_attributes_break_down_attribute_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.break_down_attributes_break_down_attribute_id_seq OWNER TO postgres;

--
-- TOC entry 4920 (class 0 OID 0)
-- Dependencies: 323
-- Name: break_down_attributes_break_down_attribute_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.break_down_attributes_break_down_attribute_id_seq OWNED BY public.break_down_attributes.break_down_attribute_id;


--
-- TOC entry 344 (class 1259 OID 40001)
-- Name: break_down_lists; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.break_down_lists (
    break_down_list_id bigint NOT NULL,
    break_down_type_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    asset_id bigint,
    job_no character varying(255) NOT NULL,
    job_date timestamp(0) without time zone,
    note text
);


ALTER TABLE public.break_down_lists OWNER TO postgres;

--
-- TOC entry 343 (class 1259 OID 40000)
-- Name: break_down_lists_break_down_list_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.break_down_lists_break_down_list_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.break_down_lists_break_down_list_id_seq OWNER TO postgres;

--
-- TOC entry 4921 (class 0 OID 0)
-- Dependencies: 343
-- Name: break_down_lists_break_down_list_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.break_down_lists_break_down_list_id_seq OWNED BY public.break_down_lists.break_down_list_id;


--
-- TOC entry 308 (class 1259 OID 39747)
-- Name: break_down_types; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.break_down_types (
    break_down_type_id bigint NOT NULL,
    break_down_type_code character varying(255) NOT NULL,
    break_down_type_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.break_down_types OWNER TO postgres;

--
-- TOC entry 307 (class 1259 OID 39746)
-- Name: break_down_types_break_down_type_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.break_down_types_break_down_type_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.break_down_types_break_down_type_id_seq OWNER TO postgres;

--
-- TOC entry 4922 (class 0 OID 0)
-- Dependencies: 307
-- Name: break_down_types_break_down_type_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.break_down_types_break_down_type_id_seq OWNED BY public.break_down_types.break_down_type_id;


--
-- TOC entry 219 (class 1259 OID 39098)
-- Name: cache; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache OWNER TO postgres;

--
-- TOC entry 220 (class 1259 OID 39105)
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache_locks OWNER TO postgres;

--
-- TOC entry 360 (class 1259 OID 40151)
-- Name: campaign_results; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.campaign_results (
    campaign_result_id bigint NOT NULL,
    campaign_id bigint NOT NULL,
    asset_id bigint NOT NULL,
    location character varying(255),
    date timestamp(0) without time zone NOT NULL,
    file character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    torpedo_values character varying(255)
);


ALTER TABLE public.campaign_results OWNER TO postgres;

--
-- TOC entry 359 (class 1259 OID 40150)
-- Name: campaign_results_campaign_result_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.campaign_results_campaign_result_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.campaign_results_campaign_result_id_seq OWNER TO postgres;

--
-- TOC entry 4923 (class 0 OID 0)
-- Dependencies: 359
-- Name: campaign_results_campaign_result_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.campaign_results_campaign_result_id_seq OWNED BY public.campaign_results.campaign_result_id;


--
-- TOC entry 358 (class 1259 OID 40137)
-- Name: campaigns; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.campaigns (
    campaign_id bigint NOT NULL,
    asset_id bigint NOT NULL,
    datasource character varying(255) NOT NULL,
    file character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    job_date_time timestamp(0) without time zone NOT NULL,
    job_no character varying(255),
    script character varying(255)
);


ALTER TABLE public.campaigns OWNER TO postgres;

--
-- TOC entry 357 (class 1259 OID 40136)
-- Name: campaigns_campaign_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.campaigns_campaign_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.campaigns_campaign_id_seq OWNER TO postgres;

--
-- TOC entry 4924 (class 0 OID 0)
-- Dependencies: 357
-- Name: campaigns_campaign_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.campaigns_campaign_id_seq OWNED BY public.campaigns.campaign_id;


--
-- TOC entry 298 (class 1259 OID 39642)
-- Name: check_asset_types; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.check_asset_types (
    check_asset_type_id bigint NOT NULL,
    check_id bigint NOT NULL,
    asset_type_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.check_asset_types OWNER TO postgres;

--
-- TOC entry 297 (class 1259 OID 39641)
-- Name: check_asset_types_check_asset_type_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.check_asset_types_check_asset_type_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.check_asset_types_check_asset_type_id_seq OWNER TO postgres;

--
-- TOC entry 4925 (class 0 OID 0)
-- Dependencies: 297
-- Name: check_asset_types_check_asset_type_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.check_asset_types_check_asset_type_id_seq OWNED BY public.check_asset_types.check_asset_type_id;


--
-- TOC entry 254 (class 1259 OID 39272)
-- Name: checks; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.checks (
    check_id bigint NOT NULL,
    field_name character varying(255) NOT NULL,
    field_type character varying(50) NOT NULL,
    default_value character varying(100),
    is_required boolean DEFAULT false NOT NULL,
    lcl double precision,
    ucl double precision,
    field_values text,
    "order" integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    department_id bigint
);


ALTER TABLE public.checks OWNER TO postgres;

--
-- TOC entry 253 (class 1259 OID 39271)
-- Name: checks_check_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.checks_check_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.checks_check_id_seq OWNER TO postgres;

--
-- TOC entry 4926 (class 0 OID 0)
-- Dependencies: 253
-- Name: checks_check_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.checks_check_id_seq OWNED BY public.checks.check_id;


--
-- TOC entry 290 (class 1259 OID 39581)
-- Name: consents; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.consents (
    consent_id bigint NOT NULL,
    user_id bigint NOT NULL,
    consent boolean NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.consents OWNER TO postgres;

--
-- TOC entry 289 (class 1259 OID 39580)
-- Name: consents_consent_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.consents_consent_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.consents_consent_id_seq OWNER TO postgres;

--
-- TOC entry 4927 (class 0 OID 0)
-- Dependencies: 289
-- Name: consents_consent_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.consents_consent_id_seq OWNED BY public.consents.consent_id;


--
-- TOC entry 342 (class 1259 OID 39984)
-- Name: data_source_asset_types; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.data_source_asset_types (
    data_source_asset_type_id bigint NOT NULL,
    data_source_id bigint NOT NULL,
    asset_type_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.data_source_asset_types OWNER TO postgres;

--
-- TOC entry 341 (class 1259 OID 39983)
-- Name: data_source_asset_types_data_source_asset_type_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.data_source_asset_types_data_source_asset_type_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.data_source_asset_types_data_source_asset_type_id_seq OWNER TO postgres;

--
-- TOC entry 4928 (class 0 OID 0)
-- Dependencies: 341
-- Name: data_source_asset_types_data_source_asset_type_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.data_source_asset_types_data_source_asset_type_id_seq OWNED BY public.data_source_asset_types.data_source_asset_type_id;


--
-- TOC entry 328 (class 1259 OID 39875)
-- Name: data_source_attribute_types; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.data_source_attribute_types (
    data_source_attribute_type_id bigint NOT NULL,
    data_source_attribute_id bigint NOT NULL,
    data_source_type_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.data_source_attribute_types OWNER TO postgres;

--
-- TOC entry 327 (class 1259 OID 39874)
-- Name: data_source_attribute_types_data_source_attribute_type_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.data_source_attribute_types_data_source_attribute_type_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.data_source_attribute_types_data_source_attribute_type_id_seq OWNER TO postgres;

--
-- TOC entry 4929 (class 0 OID 0)
-- Dependencies: 327
-- Name: data_source_attribute_types_data_source_attribute_type_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.data_source_attribute_types_data_source_attribute_type_id_seq OWNED BY public.data_source_attribute_types.data_source_attribute_type_id;


--
-- TOC entry 354 (class 1259 OID 40081)
-- Name: data_source_attribute_values; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.data_source_attribute_values (
    data_source_attribute_value_id bigint NOT NULL,
    data_source_id bigint NOT NULL,
    data_source_attribute_id bigint NOT NULL,
    field_value character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.data_source_attribute_values OWNER TO postgres;

--
-- TOC entry 353 (class 1259 OID 40080)
-- Name: data_source_attribute_values_data_source_attribute_value_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.data_source_attribute_values_data_source_attribute_value_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.data_source_attribute_values_data_source_attribute_value_id_seq OWNER TO postgres;

--
-- TOC entry 4930 (class 0 OID 0)
-- Dependencies: 353
-- Name: data_source_attribute_values_data_source_attribute_value_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.data_source_attribute_values_data_source_attribute_value_id_seq OWNED BY public.data_source_attribute_values.data_source_attribute_value_id;


--
-- TOC entry 318 (class 1259 OID 39798)
-- Name: data_source_attributes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.data_source_attributes (
    data_source_attribute_id bigint NOT NULL,
    field_name character varying(100) NOT NULL,
    display_name character varying(100) NOT NULL,
    field_type character varying(50) NOT NULL,
    field_values text,
    field_length integer NOT NULL,
    is_required boolean DEFAULT false NOT NULL,
    user_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    list_parameter_id bigint
);


ALTER TABLE public.data_source_attributes OWNER TO postgres;

--
-- TOC entry 317 (class 1259 OID 39797)
-- Name: data_source_attributes_data_source_attribute_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.data_source_attributes_data_source_attribute_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.data_source_attributes_data_source_attribute_id_seq OWNER TO postgres;

--
-- TOC entry 4931 (class 0 OID 0)
-- Dependencies: 317
-- Name: data_source_attributes_data_source_attribute_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.data_source_attributes_data_source_attribute_id_seq OWNED BY public.data_source_attributes.data_source_attribute_id;


--
-- TOC entry 306 (class 1259 OID 39740)
-- Name: data_source_types; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.data_source_types (
    data_source_type_id bigint NOT NULL,
    data_source_type_code character varying(100) NOT NULL,
    data_source_type_name character varying(100) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.data_source_types OWNER TO postgres;

--
-- TOC entry 305 (class 1259 OID 39739)
-- Name: data_source_types_data_source_type_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.data_source_types_data_source_type_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.data_source_types_data_source_type_id_seq OWNER TO postgres;

--
-- TOC entry 4932 (class 0 OID 0)
-- Dependencies: 305
-- Name: data_source_types_data_source_type_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.data_source_types_data_source_type_id_seq OWNED BY public.data_source_types.data_source_type_id;


--
-- TOC entry 340 (class 1259 OID 39972)
-- Name: data_sources; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.data_sources (
    data_source_id bigint NOT NULL,
    data_source_type_id bigint NOT NULL,
    data_source_code character varying(100) NOT NULL,
    data_source_name character varying(100) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.data_sources OWNER TO postgres;

--
-- TOC entry 339 (class 1259 OID 39971)
-- Name: data_sources_data_source_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.data_sources_data_source_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.data_sources_data_source_id_seq OWNER TO postgres;

--
-- TOC entry 4933 (class 0 OID 0)
-- Dependencies: 339
-- Name: data_sources_data_source_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.data_sources_data_source_id_seq OWNED BY public.data_sources.data_source_id;


--
-- TOC entry 242 (class 1259 OID 39228)
-- Name: departments; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.departments (
    department_id bigint NOT NULL,
    department_code character varying(255) NOT NULL,
    department_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.departments OWNER TO postgres;

--
-- TOC entry 241 (class 1259 OID 39227)
-- Name: departments_department_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.departments_department_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.departments_department_id_seq OWNER TO postgres;

--
-- TOC entry 4934 (class 0 OID 0)
-- Dependencies: 241
-- Name: departments_department_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.departments_department_id_seq OWNED BY public.departments.department_id;


--
-- TOC entry 418 (class 1259 OID 41084)
-- Name: downloaded_reports; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.downloaded_reports (
    download_report_id bigint NOT NULL,
    user_id bigint,
    date_time timestamp(0) without time zone NOT NULL,
    file_name character varying(255) NOT NULL,
    report_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.downloaded_reports OWNER TO postgres;

--
-- TOC entry 417 (class 1259 OID 41083)
-- Name: downloaded_reports_download_report_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.downloaded_reports_download_report_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.downloaded_reports_download_report_id_seq OWNER TO postgres;

--
-- TOC entry 4935 (class 0 OID 0)
-- Dependencies: 417
-- Name: downloaded_reports_download_report_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.downloaded_reports_download_report_id_seq OWNED BY public.downloaded_reports.download_report_id;


--
-- TOC entry 264 (class 1259 OID 39334)
-- Name: equipment; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.equipment (
    equipment_id bigint NOT NULL,
    plant_id bigint NOT NULL,
    equipment_type_id bigint NOT NULL,
    equipment_code character varying(255) NOT NULL,
    equipment_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    description text
);


ALTER TABLE public.equipment OWNER TO postgres;

--
-- TOC entry 263 (class 1259 OID 39333)
-- Name: equipment_equipment_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.equipment_equipment_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.equipment_equipment_id_seq OWNER TO postgres;

--
-- TOC entry 4936 (class 0 OID 0)
-- Dependencies: 263
-- Name: equipment_equipment_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.equipment_equipment_id_seq OWNED BY public.equipment.equipment_id;


--
-- TOC entry 246 (class 1259 OID 39244)
-- Name: equipment_types; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.equipment_types (
    equipment_type_id bigint NOT NULL,
    equipment_type_code character varying(100) NOT NULL,
    equipment_type_name character varying(100) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.equipment_types OWNER TO postgres;

--
-- TOC entry 245 (class 1259 OID 39243)
-- Name: equipment_types_equipment_type_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.equipment_types_equipment_type_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.equipment_types_equipment_type_id_seq OWNER TO postgres;

--
-- TOC entry 4937 (class 0 OID 0)
-- Dependencies: 245
-- Name: equipment_types_equipment_type_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.equipment_types_equipment_type_id_seq OWNED BY public.equipment_types.equipment_type_id;


--
-- TOC entry 225 (class 1259 OID 39130)
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.failed_jobs OWNER TO postgres;

--
-- TOC entry 224 (class 1259 OID 39129)
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.failed_jobs_id_seq OWNER TO postgres;

--
-- TOC entry 4938 (class 0 OID 0)
-- Dependencies: 224
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- TOC entry 240 (class 1259 OID 39219)
-- Name: frequencies; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.frequencies (
    frequency_id bigint NOT NULL,
    frequency_code character varying(255) NOT NULL,
    frequency_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.frequencies OWNER TO postgres;

--
-- TOC entry 239 (class 1259 OID 39218)
-- Name: frequencies_frequency_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.frequencies_frequency_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.frequencies_frequency_id_seq OWNER TO postgres;

--
-- TOC entry 4939 (class 0 OID 0)
-- Dependencies: 239
-- Name: frequencies_frequency_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.frequencies_frequency_id_seq OWNED BY public.frequencies.frequency_id;


--
-- TOC entry 310 (class 1259 OID 39756)
-- Name: functionals; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.functionals (
    functional_id bigint NOT NULL,
    functional_code character varying(255) NOT NULL,
    functional_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.functionals OWNER TO postgres;

--
-- TOC entry 309 (class 1259 OID 39755)
-- Name: functionals_functional_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.functionals_functional_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.functionals_functional_id_seq OWNER TO postgres;

--
-- TOC entry 4940 (class 0 OID 0)
-- Dependencies: 309
-- Name: functionals_functional_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.functionals_functional_id_seq OWNED BY public.functionals.functional_id;


--
-- TOC entry 223 (class 1259 OID 39122)
-- Name: job_batches; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);


ALTER TABLE public.job_batches OWNER TO postgres;

--
-- TOC entry 222 (class 1259 OID 39113)
-- Name: jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


ALTER TABLE public.jobs OWNER TO postgres;

--
-- TOC entry 221 (class 1259 OID 39112)
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.jobs_id_seq OWNER TO postgres;

--
-- TOC entry 4941 (class 0 OID 0)
-- Dependencies: 221
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- TOC entry 356 (class 1259 OID 40098)
-- Name: list_parameters; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.list_parameters (
    list_parameter_id bigint NOT NULL,
    list_parameter_name character varying(255) NOT NULL,
    field_values text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.list_parameters OWNER TO postgres;

--
-- TOC entry 355 (class 1259 OID 40097)
-- Name: list_parameters_list_parameter_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.list_parameters_list_parameter_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.list_parameters_list_parameter_id_seq OWNER TO postgres;

--
-- TOC entry 4942 (class 0 OID 0)
-- Dependencies: 355
-- Name: list_parameters_list_parameter_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.list_parameters_list_parameter_id_seq OWNED BY public.list_parameters.list_parameter_id;


--
-- TOC entry 218 (class 1259 OID 39092)
-- Name: migrations; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO postgres;

--
-- TOC entry 217 (class 1259 OID 39091)
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.migrations_id_seq OWNER TO postgres;

--
-- TOC entry 4943 (class 0 OID 0)
-- Dependencies: 217
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- TOC entry 258 (class 1259 OID 39294)
-- Name: modules; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.modules (
    module_id bigint NOT NULL,
    module_name character varying(255) NOT NULL,
    description character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.modules OWNER TO postgres;

--
-- TOC entry 257 (class 1259 OID 39293)
-- Name: modules_module_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.modules_module_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.modules_module_id_seq OWNER TO postgres;

--
-- TOC entry 4944 (class 0 OID 0)
-- Dependencies: 257
-- Name: modules_module_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.modules_module_id_seq OWNED BY public.modules.module_id;


--
-- TOC entry 238 (class 1259 OID 39207)
-- Name: otps; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.otps (
    otp_id bigint NOT NULL,
    user_id bigint NOT NULL,
    otp character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.otps OWNER TO postgres;

--
-- TOC entry 237 (class 1259 OID 39206)
-- Name: otps_otp_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.otps_otp_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.otps_otp_id_seq OWNER TO postgres;

--
-- TOC entry 4945 (class 0 OID 0)
-- Dependencies: 237
-- Name: otps_otp_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.otps_otp_id_seq OWNED BY public.otps.otp_id;


--
-- TOC entry 235 (class 1259 OID 39189)
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.password_reset_tokens (
    id bigint NOT NULL,
    email character varying(255) NOT NULL,
    otp character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.password_reset_tokens OWNER TO postgres;

--
-- TOC entry 234 (class 1259 OID 39188)
-- Name: password_reset_tokens_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.password_reset_tokens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.password_reset_tokens_id_seq OWNER TO postgres;

--
-- TOC entry 4946 (class 0 OID 0)
-- Dependencies: 234
-- Name: password_reset_tokens_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.password_reset_tokens_id_seq OWNED BY public.password_reset_tokens.id;


--
-- TOC entry 256 (class 1259 OID 39282)
-- Name: personal_access_tokens; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.personal_access_tokens (
    id bigint NOT NULL,
    tokenable_type character varying(255) NOT NULL,
    tokenable_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    token character varying(64) NOT NULL,
    abilities text,
    last_used_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.personal_access_tokens OWNER TO postgres;

--
-- TOC entry 255 (class 1259 OID 39281)
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.personal_access_tokens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.personal_access_tokens_id_seq OWNER TO postgres;

--
-- TOC entry 4947 (class 0 OID 0)
-- Dependencies: 255
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.personal_access_tokens_id_seq OWNED BY public.personal_access_tokens.id;


--
-- TOC entry 229 (class 1259 OID 39149)
-- Name: plants; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.plants (
    plant_id bigint NOT NULL,
    plant_code character varying(100) NOT NULL,
    plant_name character varying(100) NOT NULL,
    area_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    latitude character varying(255),
    longitude character varying(255),
    radius character varying(255)
);


ALTER TABLE public.plants OWNER TO postgres;

--
-- TOC entry 228 (class 1259 OID 39148)
-- Name: plants_plant_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.plants_plant_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.plants_plant_id_seq OWNER TO postgres;

--
-- TOC entry 4948 (class 0 OID 0)
-- Dependencies: 228
-- Name: plants_plant_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.plants_plant_id_seq OWNED BY public.plants.plant_id;


--
-- TOC entry 276 (class 1259 OID 39433)
-- Name: reasons; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.reasons (
    reason_id bigint NOT NULL,
    reason_code character varying(100) NOT NULL,
    reason_name character varying(100) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.reasons OWNER TO postgres;

--
-- TOC entry 275 (class 1259 OID 39432)
-- Name: reasons_reason_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.reasons_reason_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.reasons_reason_id_seq OWNER TO postgres;

--
-- TOC entry 4949 (class 0 OID 0)
-- Dependencies: 275
-- Name: reasons_reason_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.reasons_reason_id_seq OWNED BY public.reasons.reason_id;


--
-- TOC entry 262 (class 1259 OID 39317)
-- Name: role_abilities; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.role_abilities (
    role_ability_id bigint NOT NULL,
    role_id bigint NOT NULL,
    ability_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.role_abilities OWNER TO postgres;

--
-- TOC entry 261 (class 1259 OID 39316)
-- Name: role_abilities_role_ability_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.role_abilities_role_ability_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.role_abilities_role_ability_id_seq OWNER TO postgres;

--
-- TOC entry 4950 (class 0 OID 0)
-- Dependencies: 261
-- Name: role_abilities_role_ability_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.role_abilities_role_ability_id_seq OWNED BY public.role_abilities.role_ability_id;


--
-- TOC entry 231 (class 1259 OID 39161)
-- Name: roles; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.roles (
    role_id bigint NOT NULL,
    role character varying(100) NOT NULL,
    description text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.roles OWNER TO postgres;

--
-- TOC entry 230 (class 1259 OID 39160)
-- Name: roles_role_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.roles_role_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.roles_role_id_seq OWNER TO postgres;

--
-- TOC entry 4951 (class 0 OID 0)
-- Dependencies: 230
-- Name: roles_role_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.roles_role_id_seq OWNED BY public.roles.role_id;


--
-- TOC entry 244 (class 1259 OID 39237)
-- Name: sections; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sections (
    section_id bigint NOT NULL,
    section_code character varying(100) NOT NULL,
    section_name character varying(100) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.sections OWNER TO postgres;

--
-- TOC entry 243 (class 1259 OID 39236)
-- Name: sections_section_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.sections_section_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.sections_section_id_seq OWNER TO postgres;

--
-- TOC entry 4952 (class 0 OID 0)
-- Dependencies: 243
-- Name: sections_section_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.sections_section_id_seq OWNED BY public.sections.section_id;


--
-- TOC entry 302 (class 1259 OID 39676)
-- Name: service_asset_types; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.service_asset_types (
    service_asset_type_id bigint NOT NULL,
    service_id bigint NOT NULL,
    asset_type_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.service_asset_types OWNER TO postgres;

--
-- TOC entry 301 (class 1259 OID 39675)
-- Name: service_asset_types_service_asset_type_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.service_asset_types_service_asset_type_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.service_asset_types_service_asset_type_id_seq OWNER TO postgres;

--
-- TOC entry 4953 (class 0 OID 0)
-- Dependencies: 301
-- Name: service_asset_types_service_asset_type_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.service_asset_types_service_asset_type_id_seq OWNED BY public.service_asset_types.service_asset_type_id;


--
-- TOC entry 332 (class 1259 OID 39909)
-- Name: service_attribute_types; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.service_attribute_types (
    service_attribute_type_id bigint NOT NULL,
    service_attribute_id bigint NOT NULL,
    service_type_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.service_attribute_types OWNER TO postgres;

--
-- TOC entry 331 (class 1259 OID 39908)
-- Name: service_attribute_types_service_attribute_type_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.service_attribute_types_service_attribute_type_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.service_attribute_types_service_attribute_type_id_seq OWNER TO postgres;

--
-- TOC entry 4954 (class 0 OID 0)
-- Dependencies: 331
-- Name: service_attribute_types_service_attribute_type_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.service_attribute_types_service_attribute_type_id_seq OWNED BY public.service_attribute_types.service_attribute_type_id;


--
-- TOC entry 348 (class 1259 OID 40030)
-- Name: service_attribute_values; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.service_attribute_values (
    service_attribute_value_id bigint NOT NULL,
    service_id bigint NOT NULL,
    service_attribute_id bigint NOT NULL,
    field_value character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.service_attribute_values OWNER TO postgres;

--
-- TOC entry 347 (class 1259 OID 40029)
-- Name: service_attribute_values_service_attribute_value_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.service_attribute_values_service_attribute_value_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.service_attribute_values_service_attribute_value_id_seq OWNER TO postgres;

--
-- TOC entry 4955 (class 0 OID 0)
-- Dependencies: 347
-- Name: service_attribute_values_service_attribute_value_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.service_attribute_values_service_attribute_value_id_seq OWNED BY public.service_attribute_values.service_attribute_value_id;


--
-- TOC entry 322 (class 1259 OID 39828)
-- Name: service_attributes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.service_attributes (
    service_attribute_id bigint NOT NULL,
    field_name character varying(100) NOT NULL,
    display_name character varying(100) NOT NULL,
    field_type character varying(50) NOT NULL,
    field_values text,
    field_length integer NOT NULL,
    is_required boolean DEFAULT false NOT NULL,
    user_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    list_parameter_id bigint
);


ALTER TABLE public.service_attributes OWNER TO postgres;

--
-- TOC entry 321 (class 1259 OID 39827)
-- Name: service_attributes_service_attribute_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.service_attributes_service_attribute_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.service_attributes_service_attribute_id_seq OWNER TO postgres;

--
-- TOC entry 4956 (class 0 OID 0)
-- Dependencies: 321
-- Name: service_attributes_service_attribute_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.service_attributes_service_attribute_id_seq OWNED BY public.service_attributes.service_attribute_id;


--
-- TOC entry 252 (class 1259 OID 39265)
-- Name: service_type; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.service_type (
    service_type_id bigint NOT NULL,
    service_type_code character varying(100) NOT NULL,
    service_type_name character varying(100) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.service_type OWNER TO postgres;

--
-- TOC entry 251 (class 1259 OID 39264)
-- Name: service_type_service_type_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.service_type_service_type_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.service_type_service_type_id_seq OWNER TO postgres;

--
-- TOC entry 4957 (class 0 OID 0)
-- Dependencies: 251
-- Name: service_type_service_type_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.service_type_service_type_id_seq OWNED BY public.service_type.service_type_id;


--
-- TOC entry 268 (class 1259 OID 39365)
-- Name: services; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.services (
    service_id bigint NOT NULL,
    service_type_id bigint NOT NULL,
    service_code character varying(100) NOT NULL,
    service_name character varying(100) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.services OWNER TO postgres;

--
-- TOC entry 267 (class 1259 OID 39364)
-- Name: services_service_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.services_service_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.services_service_id_seq OWNER TO postgres;

--
-- TOC entry 4958 (class 0 OID 0)
-- Dependencies: 267
-- Name: services_service_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.services_service_id_seq OWNED BY public.services.service_id;


--
-- TOC entry 236 (class 1259 OID 39197)
-- Name: sessions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


ALTER TABLE public.sessions OWNER TO postgres;

--
-- TOC entry 300 (class 1259 OID 39659)
-- Name: spare_asset_types; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.spare_asset_types (
    spare_asset_type_id bigint NOT NULL,
    spare_id bigint NOT NULL,
    asset_type_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.spare_asset_types OWNER TO postgres;

--
-- TOC entry 299 (class 1259 OID 39658)
-- Name: spare_asset_types_spare_asset_type_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.spare_asset_types_spare_asset_type_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.spare_asset_types_spare_asset_type_id_seq OWNER TO postgres;

--
-- TOC entry 4959 (class 0 OID 0)
-- Dependencies: 299
-- Name: spare_asset_types_spare_asset_type_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.spare_asset_types_spare_asset_type_id_seq OWNED BY public.spare_asset_types.spare_asset_type_id;


--
-- TOC entry 326 (class 1259 OID 39858)
-- Name: spare_attribute_types; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.spare_attribute_types (
    spare_attribute_type_id bigint NOT NULL,
    spare_attribute_id bigint NOT NULL,
    spare_type_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.spare_attribute_types OWNER TO postgres;

--
-- TOC entry 325 (class 1259 OID 39857)
-- Name: spare_attribute_types_spare_attribute_type_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.spare_attribute_types_spare_attribute_type_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.spare_attribute_types_spare_attribute_type_id_seq OWNER TO postgres;

--
-- TOC entry 4960 (class 0 OID 0)
-- Dependencies: 325
-- Name: spare_attribute_types_spare_attribute_type_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.spare_attribute_types_spare_attribute_type_id_seq OWNED BY public.spare_attribute_types.spare_attribute_type_id;


--
-- TOC entry 346 (class 1259 OID 40013)
-- Name: spare_attribute_values; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.spare_attribute_values (
    spare_attribute_value_id bigint NOT NULL,
    spare_id bigint NOT NULL,
    spare_attribute_id bigint NOT NULL,
    field_value character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.spare_attribute_values OWNER TO postgres;

--
-- TOC entry 345 (class 1259 OID 40012)
-- Name: spare_attribute_values_spare_attribute_value_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.spare_attribute_values_spare_attribute_value_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.spare_attribute_values_spare_attribute_value_id_seq OWNER TO postgres;

--
-- TOC entry 4961 (class 0 OID 0)
-- Dependencies: 345
-- Name: spare_attribute_values_spare_attribute_value_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.spare_attribute_values_spare_attribute_value_id_seq OWNED BY public.spare_attribute_values.spare_attribute_value_id;


--
-- TOC entry 316 (class 1259 OID 39783)
-- Name: spare_attributes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.spare_attributes (
    spare_attribute_id bigint NOT NULL,
    field_name character varying(100) NOT NULL,
    display_name character varying(100) NOT NULL,
    field_type character varying(50) NOT NULL,
    field_values text,
    field_length integer NOT NULL,
    is_required boolean DEFAULT false NOT NULL,
    user_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    list_parameter_id bigint
);


ALTER TABLE public.spare_attributes OWNER TO postgres;

--
-- TOC entry 315 (class 1259 OID 39782)
-- Name: spare_attributes_spare_attribute_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.spare_attributes_spare_attribute_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.spare_attributes_spare_attribute_id_seq OWNER TO postgres;

--
-- TOC entry 4962 (class 0 OID 0)
-- Dependencies: 315
-- Name: spare_attributes_spare_attribute_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.spare_attributes_spare_attribute_id_seq OWNED BY public.spare_attributes.spare_attribute_id;


--
-- TOC entry 250 (class 1259 OID 39258)
-- Name: spare_types; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.spare_types (
    spare_type_id bigint NOT NULL,
    spare_type_code character varying(100) NOT NULL,
    spare_type_name character varying(100) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.spare_types OWNER TO postgres;

--
-- TOC entry 249 (class 1259 OID 39257)
-- Name: spare_types_spare_type_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.spare_types_spare_type_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.spare_types_spare_type_id_seq OWNER TO postgres;

--
-- TOC entry 4963 (class 0 OID 0)
-- Dependencies: 249
-- Name: spare_types_spare_type_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.spare_types_spare_type_id_seq OWNED BY public.spare_types.spare_type_id;


--
-- TOC entry 266 (class 1259 OID 39353)
-- Name: spares; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.spares (
    spare_id bigint NOT NULL,
    spare_type_id bigint NOT NULL,
    spare_code character varying(100) NOT NULL,
    spare_name character varying(100) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.spares OWNER TO postgres;

--
-- TOC entry 265 (class 1259 OID 39352)
-- Name: spares_spare_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.spares_spare_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.spares_spare_id_seq OWNER TO postgres;

--
-- TOC entry 4964 (class 0 OID 0)
-- Dependencies: 265
-- Name: spares_spare_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.spares_spare_id_seq OWNED BY public.spares.spare_id;


--
-- TOC entry 396 (class 1259 OID 40716)
-- Name: template_attribute_values; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.template_attribute_values (
    template_attribute_value_id bigint NOT NULL,
    asset_template_id bigint NOT NULL,
    asset_attribute_id bigint NOT NULL,
    field_value character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.template_attribute_values OWNER TO postgres;

--
-- TOC entry 395 (class 1259 OID 40715)
-- Name: template_attribute_values_template_attribute_value_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.template_attribute_values_template_attribute_value_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.template_attribute_values_template_attribute_value_id_seq OWNER TO postgres;

--
-- TOC entry 4965 (class 0 OID 0)
-- Dependencies: 395
-- Name: template_attribute_values_template_attribute_value_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.template_attribute_values_template_attribute_value_id_seq OWNED BY public.template_attribute_values.template_attribute_value_id;


--
-- TOC entry 414 (class 1259 OID 41009)
-- Name: template_datasource_values; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.template_datasource_values (
    template_datasource_value_id bigint NOT NULL,
    asset_template_id bigint NOT NULL,
    asset_template_datasource_id bigint NOT NULL,
    template_zone_id bigint,
    data_source_id bigint NOT NULL,
    data_source_attribute_id bigint NOT NULL,
    field_value character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.template_datasource_values OWNER TO postgres;

--
-- TOC entry 413 (class 1259 OID 41008)
-- Name: template_datasource_values_template_datasource_value_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.template_datasource_values_template_datasource_value_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.template_datasource_values_template_datasource_value_id_seq OWNER TO postgres;

--
-- TOC entry 4966 (class 0 OID 0)
-- Dependencies: 413
-- Name: template_datasource_values_template_datasource_value_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.template_datasource_values_template_datasource_value_id_seq OWNED BY public.template_datasource_values.template_datasource_value_id;


--
-- TOC entry 394 (class 1259 OID 40699)
-- Name: template_departments; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.template_departments (
    template_department_id bigint NOT NULL,
    asset_template_id bigint NOT NULL,
    department_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.template_departments OWNER TO postgres;

--
-- TOC entry 393 (class 1259 OID 40698)
-- Name: template_departments_template_department_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.template_departments_template_department_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.template_departments_template_department_id_seq OWNER TO postgres;

--
-- TOC entry 4967 (class 0 OID 0)
-- Dependencies: 393
-- Name: template_departments_template_department_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.template_departments_template_department_id_seq OWNED BY public.template_departments.template_department_id;


--
-- TOC entry 406 (class 1259 OID 40871)
-- Name: template_service_values; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.template_service_values (
    template_service_value_id bigint NOT NULL,
    asset_template_service_id bigint NOT NULL,
    asset_template_id bigint NOT NULL,
    service_id bigint NOT NULL,
    template_zone_id bigint NOT NULL,
    service_attribute_id bigint NOT NULL,
    field_value character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.template_service_values OWNER TO postgres;

--
-- TOC entry 405 (class 1259 OID 40870)
-- Name: template_service_values_template_service_value_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.template_service_values_template_service_value_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.template_service_values_template_service_value_id_seq OWNER TO postgres;

--
-- TOC entry 4968 (class 0 OID 0)
-- Dependencies: 405
-- Name: template_service_values_template_service_value_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.template_service_values_template_service_value_id_seq OWNED BY public.template_service_values.template_service_value_id;


--
-- TOC entry 400 (class 1259 OID 40770)
-- Name: template_spare_values; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.template_spare_values (
    template_spare_value_id bigint NOT NULL,
    spare_id bigint NOT NULL,
    asset_template_id bigint NOT NULL,
    asset_template_spare_id bigint NOT NULL,
    template_zone_id bigint NOT NULL,
    spare_attribute_id bigint NOT NULL,
    field_value character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.template_spare_values OWNER TO postgres;

--
-- TOC entry 399 (class 1259 OID 40769)
-- Name: template_spare_values_template_spare_value_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.template_spare_values_template_spare_value_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.template_spare_values_template_spare_value_id_seq OWNER TO postgres;

--
-- TOC entry 4969 (class 0 OID 0)
-- Dependencies: 399
-- Name: template_spare_values_template_spare_value_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.template_spare_values_template_spare_value_id_seq OWNED BY public.template_spare_values.template_spare_value_id;


--
-- TOC entry 410 (class 1259 OID 40940)
-- Name: template_variable_values; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.template_variable_values (
    template_variable_value_id bigint NOT NULL,
    asset_template_variable_id bigint NOT NULL,
    asset_template_id bigint NOT NULL,
    variable_id bigint NOT NULL,
    template_zone_id bigint NOT NULL,
    variable_attribute_id bigint NOT NULL,
    field_value character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.template_variable_values OWNER TO postgres;

--
-- TOC entry 409 (class 1259 OID 40939)
-- Name: template_variable_values_template_variable_value_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.template_variable_values_template_variable_value_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.template_variable_values_template_variable_value_id_seq OWNER TO postgres;

--
-- TOC entry 4970 (class 0 OID 0)
-- Dependencies: 409
-- Name: template_variable_values_template_variable_value_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.template_variable_values_template_variable_value_id_seq OWNED BY public.template_variable_values.template_variable_value_id;


--
-- TOC entry 392 (class 1259 OID 40686)
-- Name: template_zones; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.template_zones (
    template_zone_id bigint NOT NULL,
    asset_template_id bigint NOT NULL,
    zone_name character varying(100) NOT NULL,
    height numeric(8,2),
    diameter numeric(8,2),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.template_zones OWNER TO postgres;

--
-- TOC entry 391 (class 1259 OID 40685)
-- Name: template_zones_template_zone_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.template_zones_template_zone_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.template_zones_template_zone_id_seq OWNER TO postgres;

--
-- TOC entry 4971 (class 0 OID 0)
-- Dependencies: 391
-- Name: template_zones_template_zone_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.template_zones_template_zone_id_seq OWNED BY public.template_zones.template_zone_id;


--
-- TOC entry 278 (class 1259 OID 39440)
-- Name: user_activities; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.user_activities (
    user_activity_id bigint NOT NULL,
    activity_no character varying(255) NOT NULL,
    activity_date timestamp(0) without time zone NOT NULL,
    user_id bigint NOT NULL,
    asset_id bigint NOT NULL,
    status character varying(255) NOT NULL,
    reason_id bigint NOT NULL,
    cost character varying(255),
    note text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    activity_status character varying(255),
    plant_id bigint NOT NULL
);


ALTER TABLE public.user_activities OWNER TO postgres;

--
-- TOC entry 277 (class 1259 OID 39439)
-- Name: user_activities_user_activity_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.user_activities_user_activity_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.user_activities_user_activity_id_seq OWNER TO postgres;

--
-- TOC entry 4972 (class 0 OID 0)
-- Dependencies: 277
-- Name: user_activities_user_activity_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.user_activities_user_activity_id_seq OWNED BY public.user_activities.user_activity_id;


--
-- TOC entry 286 (class 1259 OID 39539)
-- Name: user_asset_checks; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.user_asset_checks (
    user_asset_check_id bigint NOT NULL,
    user_check_id bigint NOT NULL,
    check_id bigint NOT NULL,
    asset_check_id bigint NOT NULL,
    field_name character varying(255) NOT NULL,
    field_type character varying(100) NOT NULL,
    default_value character varying(100),
    is_required boolean DEFAULT false NOT NULL,
    lcl double precision,
    ucl double precision,
    field_values text,
    "order" integer NOT NULL,
    value character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    remark_user_id bigint,
    remark_status boolean DEFAULT false NOT NULL,
    remarks text,
    remark_date timestamp(0) without time zone
);


ALTER TABLE public.user_asset_checks OWNER TO postgres;

--
-- TOC entry 285 (class 1259 OID 39538)
-- Name: user_asset_checks_user_asset_check_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.user_asset_checks_user_asset_check_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.user_asset_checks_user_asset_check_id_seq OWNER TO postgres;

--
-- TOC entry 4973 (class 0 OID 0)
-- Dependencies: 285
-- Name: user_asset_checks_user_asset_check_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.user_asset_checks_user_asset_check_id_seq OWNED BY public.user_asset_checks.user_asset_check_id;


--
-- TOC entry 372 (class 1259 OID 40397)
-- Name: user_asset_variables; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.user_asset_variables (
    user_asset_variable_id bigint NOT NULL,
    user_variable_id bigint NOT NULL,
    variable_id bigint NOT NULL,
    value character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    asset_zone_id bigint
);


ALTER TABLE public.user_asset_variables OWNER TO postgres;

--
-- TOC entry 371 (class 1259 OID 40396)
-- Name: user_asset_variables_user_asset_variable_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.user_asset_variables_user_asset_variable_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.user_asset_variables_user_asset_variable_id_seq OWNER TO postgres;

--
-- TOC entry 4974 (class 0 OID 0)
-- Dependencies: 371
-- Name: user_asset_variables_user_asset_variable_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.user_asset_variables_user_asset_variable_id_seq OWNED BY public.user_asset_variables.user_asset_variable_id;


--
-- TOC entry 288 (class 1259 OID 39564)
-- Name: user_check_attachments; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.user_check_attachments (
    user_check_attachment_id bigint NOT NULL,
    user_check_id bigint NOT NULL,
    attachments character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.user_check_attachments OWNER TO postgres;

--
-- TOC entry 287 (class 1259 OID 39563)
-- Name: user_check_attachments_user_check_attachment_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.user_check_attachments_user_check_attachment_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.user_check_attachments_user_check_attachment_id_seq OWNER TO postgres;

--
-- TOC entry 4975 (class 0 OID 0)
-- Dependencies: 287
-- Name: user_check_attachments_user_check_attachment_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.user_check_attachments_user_check_attachment_id_seq OWNED BY public.user_check_attachments.user_check_attachment_id;


--
-- TOC entry 284 (class 1259 OID 39515)
-- Name: user_checks; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.user_checks (
    user_check_id bigint NOT NULL,
    plant_id bigint NOT NULL,
    user_id bigint NOT NULL,
    asset_id bigint NOT NULL,
    reference_no character varying(255) NOT NULL,
    reference_date timestamp(0) without time zone NOT NULL,
    note character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    asset_zone_id bigint,
    department_id bigint
);


ALTER TABLE public.user_checks OWNER TO postgres;

--
-- TOC entry 283 (class 1259 OID 39514)
-- Name: user_checks_user_check_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.user_checks_user_check_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.user_checks_user_check_id_seq OWNER TO postgres;

--
-- TOC entry 4976 (class 0 OID 0)
-- Dependencies: 283
-- Name: user_checks_user_check_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.user_checks_user_check_id_seq OWNED BY public.user_checks.user_check_id;


--
-- TOC entry 280 (class 1259 OID 39469)
-- Name: user_services; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.user_services (
    user_service_id bigint NOT NULL,
    plant_id bigint NOT NULL,
    user_id bigint NOT NULL,
    service_no character varying(255) NOT NULL,
    asset_id bigint NOT NULL,
    service_date timestamp(0) without time zone NOT NULL,
    next_service_date timestamp(0) without time zone,
    note text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    is_latest boolean DEFAULT false
);


ALTER TABLE public.user_services OWNER TO postgres;

--
-- TOC entry 279 (class 1259 OID 39468)
-- Name: user_services_user_service_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.user_services_user_service_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.user_services_user_service_id_seq OWNER TO postgres;

--
-- TOC entry 4977 (class 0 OID 0)
-- Dependencies: 279
-- Name: user_services_user_service_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.user_services_user_service_id_seq OWNED BY public.user_services.user_service_id;


--
-- TOC entry 282 (class 1259 OID 39498)
-- Name: user_spares; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.user_spares (
    user_spare_id bigint NOT NULL,
    user_service_id bigint NOT NULL,
    spare_id bigint,
    spare_cost character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    service_id bigint,
    asset_zone_id bigint,
    service_cost character varying(255),
    quantity integer
);


ALTER TABLE public.user_spares OWNER TO postgres;

--
-- TOC entry 281 (class 1259 OID 39497)
-- Name: user_spares_user_spare_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.user_spares_user_spare_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.user_spares_user_spare_id_seq OWNER TO postgres;

--
-- TOC entry 4978 (class 0 OID 0)
-- Dependencies: 281
-- Name: user_spares_user_spare_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.user_spares_user_spare_id_seq OWNED BY public.user_spares.user_spare_id;


--
-- TOC entry 370 (class 1259 OID 40368)
-- Name: user_variables; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.user_variables (
    user_variable_id bigint NOT NULL,
    plant_id bigint NOT NULL,
    user_id bigint NOT NULL,
    asset_id bigint NOT NULL,
    job_no character varying(255) NOT NULL,
    job_date timestamp(0) without time zone NOT NULL,
    note character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.user_variables OWNER TO postgres;

--
-- TOC entry 369 (class 1259 OID 40367)
-- Name: user_variables_user_variable_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.user_variables_user_variable_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.user_variables_user_variable_id_seq OWNER TO postgres;

--
-- TOC entry 4979 (class 0 OID 0)
-- Dependencies: 369
-- Name: user_variables_user_variable_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.user_variables_user_variable_id_seq OWNED BY public.user_variables.user_variable_id;


--
-- TOC entry 233 (class 1259 OID 39170)
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    user_id bigint NOT NULL,
    name character varying(100) NOT NULL,
    email character varying(100) NOT NULL,
    password character varying(255) NOT NULL,
    mobile_no character varying(15) NOT NULL,
    role_id bigint NOT NULL,
    plant_id bigint NOT NULL,
    address text,
    avatar character varying(250),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    department_id bigint
);


ALTER TABLE public.users OWNER TO postgres;

--
-- TOC entry 232 (class 1259 OID 39169)
-- Name: users_user_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_user_id_seq OWNER TO postgres;

--
-- TOC entry 4980 (class 0 OID 0)
-- Dependencies: 232
-- Name: users_user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_user_id_seq OWNED BY public.users.user_id;


--
-- TOC entry 338 (class 1259 OID 39955)
-- Name: variable_asset_types; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.variable_asset_types (
    variable_asset_type_id bigint NOT NULL,
    variable_id bigint NOT NULL,
    asset_type_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.variable_asset_types OWNER TO postgres;

--
-- TOC entry 337 (class 1259 OID 39954)
-- Name: variable_asset_types_variable_asset_type_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.variable_asset_types_variable_asset_type_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.variable_asset_types_variable_asset_type_id_seq OWNER TO postgres;

--
-- TOC entry 4981 (class 0 OID 0)
-- Dependencies: 337
-- Name: variable_asset_types_variable_asset_type_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.variable_asset_types_variable_asset_type_id_seq OWNED BY public.variable_asset_types.variable_asset_type_id;


--
-- TOC entry 330 (class 1259 OID 39892)
-- Name: variable_attribute_types; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.variable_attribute_types (
    variable_attribute_type_id bigint NOT NULL,
    variable_attribute_id bigint NOT NULL,
    variable_type_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.variable_attribute_types OWNER TO postgres;

--
-- TOC entry 329 (class 1259 OID 39891)
-- Name: variable_attribute_types_variable_attribute_type_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.variable_attribute_types_variable_attribute_type_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.variable_attribute_types_variable_attribute_type_id_seq OWNER TO postgres;

--
-- TOC entry 4982 (class 0 OID 0)
-- Dependencies: 329
-- Name: variable_attribute_types_variable_attribute_type_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.variable_attribute_types_variable_attribute_type_id_seq OWNED BY public.variable_attribute_types.variable_attribute_type_id;


--
-- TOC entry 350 (class 1259 OID 40047)
-- Name: variable_attribute_values; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.variable_attribute_values (
    variable_attribute_value_id bigint NOT NULL,
    variable_id bigint NOT NULL,
    variable_attribute_id bigint NOT NULL,
    field_value character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.variable_attribute_values OWNER TO postgres;

--
-- TOC entry 349 (class 1259 OID 40046)
-- Name: variable_attribute_values_variable_attribute_value_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.variable_attribute_values_variable_attribute_value_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.variable_attribute_values_variable_attribute_value_id_seq OWNER TO postgres;

--
-- TOC entry 4983 (class 0 OID 0)
-- Dependencies: 349
-- Name: variable_attribute_values_variable_attribute_value_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.variable_attribute_values_variable_attribute_value_id_seq OWNED BY public.variable_attribute_values.variable_attribute_value_id;


--
-- TOC entry 320 (class 1259 OID 39813)
-- Name: variable_attributes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.variable_attributes (
    variable_attribute_id bigint NOT NULL,
    field_name character varying(100) NOT NULL,
    display_name character varying(100) NOT NULL,
    field_type character varying(50) NOT NULL,
    field_values text,
    field_length integer NOT NULL,
    is_required boolean DEFAULT false NOT NULL,
    user_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    list_parameter_id bigint
);


ALTER TABLE public.variable_attributes OWNER TO postgres;

--
-- TOC entry 319 (class 1259 OID 39812)
-- Name: variable_attributes_variable_attribute_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.variable_attributes_variable_attribute_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.variable_attributes_variable_attribute_id_seq OWNER TO postgres;

--
-- TOC entry 4984 (class 0 OID 0)
-- Dependencies: 319
-- Name: variable_attributes_variable_attribute_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.variable_attributes_variable_attribute_id_seq OWNED BY public.variable_attributes.variable_attribute_id;


--
-- TOC entry 314 (class 1259 OID 39774)
-- Name: variable_types; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.variable_types (
    variable_type_id bigint NOT NULL,
    variable_type_code character varying(255) NOT NULL,
    variable_type_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.variable_types OWNER TO postgres;

--
-- TOC entry 313 (class 1259 OID 39773)
-- Name: variable_types_variable_type_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.variable_types_variable_type_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.variable_types_variable_type_id_seq OWNER TO postgres;

--
-- TOC entry 4985 (class 0 OID 0)
-- Dependencies: 313
-- Name: variable_types_variable_type_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.variable_types_variable_type_id_seq OWNED BY public.variable_types.variable_type_id;


--
-- TOC entry 336 (class 1259 OID 39943)
-- Name: variables; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.variables (
    variable_id bigint NOT NULL,
    variable_type_id bigint NOT NULL,
    variable_code character varying(100) NOT NULL,
    variable_name character varying(100) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.variables OWNER TO postgres;

--
-- TOC entry 335 (class 1259 OID 39942)
-- Name: variables_variable_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.variables_variable_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.variables_variable_id_seq OWNER TO postgres;

--
-- TOC entry 4986 (class 0 OID 0)
-- Dependencies: 335
-- Name: variables_variable_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.variables_variable_id_seq OWNED BY public.variables.variable_id;


--
-- TOC entry 3977 (class 2604 OID 39306)
-- Name: abilities ability_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.abilities ALTER COLUMN ability_id SET DEFAULT nextval('public.abilities_ability_id_seq'::regclass);


--
-- TOC entry 4007 (class 2604 OID 39768)
-- Name: accessory_types accessory_type_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.accessory_types ALTER COLUMN accessory_type_id SET DEFAULT nextval('public.accessory_types_accessory_type_id_seq'::regclass);


--
-- TOC entry 4045 (class 2604 OID 40466)
-- Name: activity_attribute_types activity_attribute_type_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.activity_attribute_types ALTER COLUMN activity_attribute_type_id SET DEFAULT nextval('public.activity_attribute_types_activity_attribute_type_id_seq'::regclass);


--
-- TOC entry 4047 (class 2604 OID 40500)
-- Name: activity_attribute_values activity_attribute_value_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.activity_attribute_values ALTER COLUMN activity_attribute_value_id SET DEFAULT nextval('public.activity_attribute_values_activity_attribute_value_id_seq'::regclass);


--
-- TOC entry 4043 (class 2604 OID 40446)
-- Name: activity_attributes activity_attribute_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.activity_attributes ALTER COLUMN activity_attribute_id SET DEFAULT nextval('public.activity_attributes_activity_attribute_id_seq'::regclass);


--
-- TOC entry 3960 (class 2604 OID 39145)
-- Name: areas area_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.areas ALTER COLUMN area_id SET DEFAULT nextval('public.areas_area_id_seq'::regclass);


--
-- TOC entry 4040 (class 2604 OID 40322)
-- Name: asset_accessories asset_accessory_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_accessories ALTER COLUMN asset_accessory_id SET DEFAULT nextval('public.asset_accessories_asset_accessory_id_seq'::regclass);


--
-- TOC entry 3998 (class 2604 OID 39611)
-- Name: asset_attribute_types asset_attribute_type_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_attribute_types ALTER COLUMN asset_attribute_type_id SET DEFAULT nextval('public.asset_attribute_types_asset_attribute_type_id_seq'::regclass);


--
-- TOC entry 3999 (class 2604 OID 39628)
-- Name: asset_attribute_values asset_attribute_value_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_attribute_values ALTER COLUMN asset_attribute_value_id SET DEFAULT nextval('public.asset_attribute_values_asset_attribute_value_id_seq'::regclass);


--
-- TOC entry 3996 (class 2604 OID 39596)
-- Name: asset_attributes asset_attribute_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_attributes ALTER COLUMN asset_attribute_id SET DEFAULT nextval('public.asset_attributes_asset_attribute_id_seq'::regclass);


--
-- TOC entry 3984 (class 2604 OID 39414)
-- Name: asset_checks asset_check_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_checks ALTER COLUMN asset_check_id SET DEFAULT nextval('public.asset_checks_asset_check_id_seq'::regclass);


--
-- TOC entry 4051 (class 2604 OID 40618)
-- Name: asset_data_source_values asset_data_source_value_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_data_source_values ALTER COLUMN asset_data_source_value_id SET DEFAULT nextval('public.asset_data_source_values_asset_data_source_value_id_seq'::regclass);


--
-- TOC entry 4039 (class 2604 OID 40279)
-- Name: asset_data_sources asset_data_source_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_data_sources ALTER COLUMN asset_data_source_id SET DEFAULT nextval('public.asset_data_sources_asset_data_source_id_seq'::regclass);


--
-- TOC entry 4046 (class 2604 OID 40483)
-- Name: asset_departments asset_department_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_departments ALTER COLUMN asset_department_id SET DEFAULT nextval('public.asset_departments_asset_department_id_seq'::regclass);


--
-- TOC entry 4049 (class 2604 OID 40554)
-- Name: asset_service_values asset_service_value_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_service_values ALTER COLUMN asset_service_value_id SET DEFAULT nextval('public.asset_service_values_asset_service_value_id_seq'::regclass);


--
-- TOC entry 4003 (class 2604 OID 39696)
-- Name: asset_services asset_service_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_services ALTER COLUMN asset_service_id SET DEFAULT nextval('public.asset_services_asset_service_id_seq'::regclass);


--
-- TOC entry 4048 (class 2604 OID 40522)
-- Name: asset_spare_values asset_spare_value_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_spare_values ALTER COLUMN asset_spare_value_id SET DEFAULT nextval('public.asset_spare_values_asset_spare_value_id_seq'::regclass);


--
-- TOC entry 3983 (class 2604 OID 39392)
-- Name: asset_spares asset_spare_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_spares ALTER COLUMN asset_spare_id SET DEFAULT nextval('public.asset_spares_asset_spare_id_seq'::regclass);


--
-- TOC entry 4065 (class 2604 OID 41044)
-- Name: asset_template_accessories asset_template_accessory_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_accessories ALTER COLUMN asset_template_accessory_id SET DEFAULT nextval('public.asset_template_accessories_asset_template_accessory_id_seq'::regclass);


--
-- TOC entry 4058 (class 2604 OID 40805)
-- Name: asset_template_checks asset_template_check_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_checks ALTER COLUMN asset_template_check_id SET DEFAULT nextval('public.asset_template_checks_asset_template_check_id_seq'::regclass);


--
-- TOC entry 4063 (class 2604 OID 40975)
-- Name: asset_template_datasources asset_template_datasource_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_datasources ALTER COLUMN asset_template_datasource_id SET DEFAULT nextval('public.asset_template_datasources_asset_template_datasource_id_seq'::regclass);


--
-- TOC entry 4059 (class 2604 OID 40837)
-- Name: asset_template_services asset_template_service_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_services ALTER COLUMN asset_template_service_id SET DEFAULT nextval('public.asset_template_services_asset_template_service_id_seq'::regclass);


--
-- TOC entry 4056 (class 2604 OID 40736)
-- Name: asset_template_spares asset_template_spare_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_spares ALTER COLUMN asset_template_spare_id SET DEFAULT nextval('public.asset_template_spares_asset_template_spare_id_seq'::regclass);


--
-- TOC entry 4061 (class 2604 OID 40906)
-- Name: asset_template_variables asset_template_variable_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_variables ALTER COLUMN asset_template_variable_id SET DEFAULT nextval('public.asset_template_variables_asset_template_variable_id_seq'::regclass);


--
-- TOC entry 4052 (class 2604 OID 40655)
-- Name: asset_templates asset_template_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_templates ALTER COLUMN asset_template_id SET DEFAULT nextval('public.asset_templates_asset_template_id_seq'::regclass);


--
-- TOC entry 3970 (class 2604 OID 39254)
-- Name: asset_type asset_type_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_type ALTER COLUMN asset_type_id SET DEFAULT nextval('public.asset_type_asset_type_id_seq'::regclass);


--
-- TOC entry 4050 (class 2604 OID 40586)
-- Name: asset_variable_values asset_variable_value_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_variable_values ALTER COLUMN asset_variable_value_id SET DEFAULT nextval('public.asset_variable_values_asset_variable_value_id_seq'::regclass);


--
-- TOC entry 4038 (class 2604 OID 40236)
-- Name: asset_variables asset_variable_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_variables ALTER COLUMN asset_variable_id SET DEFAULT nextval('public.asset_variables_asset_variable_id_seq'::regclass);


--
-- TOC entry 4037 (class 2604 OID 40183)
-- Name: asset_zones asset_zone_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_zones ALTER COLUMN asset_zone_id SET DEFAULT nextval('public.asset_zones_asset_zone_id_seq'::regclass);


--
-- TOC entry 3982 (class 2604 OID 39375)
-- Name: assets asset_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assets ALTER COLUMN asset_id SET DEFAULT nextval('public.assets_asset_id_seq'::regclass);


--
-- TOC entry 4023 (class 2604 OID 39929)
-- Name: break_down_attribute_types break_down_attribute_type_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.break_down_attribute_types ALTER COLUMN break_down_attribute_type_id SET DEFAULT nextval('public.break_down_attribute_types_break_down_attribute_type_id_seq'::regclass);


--
-- TOC entry 4032 (class 2604 OID 40067)
-- Name: break_down_attribute_values break_down_attribute_value_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.break_down_attribute_values ALTER COLUMN break_down_attribute_value_id SET DEFAULT nextval('public.break_down_attribute_values_break_down_attribute_value_id_seq'::regclass);


--
-- TOC entry 4017 (class 2604 OID 39846)
-- Name: break_down_attributes break_down_attribute_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.break_down_attributes ALTER COLUMN break_down_attribute_id SET DEFAULT nextval('public.break_down_attributes_break_down_attribute_id_seq'::regclass);


--
-- TOC entry 4028 (class 2604 OID 40004)
-- Name: break_down_lists break_down_list_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.break_down_lists ALTER COLUMN break_down_list_id SET DEFAULT nextval('public.break_down_lists_break_down_list_id_seq'::regclass);


--
-- TOC entry 4005 (class 2604 OID 39750)
-- Name: break_down_types break_down_type_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.break_down_types ALTER COLUMN break_down_type_id SET DEFAULT nextval('public.break_down_types_break_down_type_id_seq'::regclass);


--
-- TOC entry 4036 (class 2604 OID 40154)
-- Name: campaign_results campaign_result_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.campaign_results ALTER COLUMN campaign_result_id SET DEFAULT nextval('public.campaign_results_campaign_result_id_seq'::regclass);


--
-- TOC entry 4035 (class 2604 OID 40140)
-- Name: campaigns campaign_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.campaigns ALTER COLUMN campaign_id SET DEFAULT nextval('public.campaigns_campaign_id_seq'::regclass);


--
-- TOC entry 4000 (class 2604 OID 39645)
-- Name: check_asset_types check_asset_type_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.check_asset_types ALTER COLUMN check_asset_type_id SET DEFAULT nextval('public.check_asset_types_check_asset_type_id_seq'::regclass);


--
-- TOC entry 3973 (class 2604 OID 39275)
-- Name: checks check_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.checks ALTER COLUMN check_id SET DEFAULT nextval('public.checks_check_id_seq'::regclass);


--
-- TOC entry 3995 (class 2604 OID 39584)
-- Name: consents consent_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.consents ALTER COLUMN consent_id SET DEFAULT nextval('public.consents_consent_id_seq'::regclass);


--
-- TOC entry 4027 (class 2604 OID 39987)
-- Name: data_source_asset_types data_source_asset_type_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.data_source_asset_types ALTER COLUMN data_source_asset_type_id SET DEFAULT nextval('public.data_source_asset_types_data_source_asset_type_id_seq'::regclass);


--
-- TOC entry 4020 (class 2604 OID 39878)
-- Name: data_source_attribute_types data_source_attribute_type_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.data_source_attribute_types ALTER COLUMN data_source_attribute_type_id SET DEFAULT nextval('public.data_source_attribute_types_data_source_attribute_type_id_seq'::regclass);


--
-- TOC entry 4033 (class 2604 OID 40084)
-- Name: data_source_attribute_values data_source_attribute_value_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.data_source_attribute_values ALTER COLUMN data_source_attribute_value_id SET DEFAULT nextval('public.data_source_attribute_values_data_source_attribute_value_id_seq'::regclass);


--
-- TOC entry 4011 (class 2604 OID 39801)
-- Name: data_source_attributes data_source_attribute_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.data_source_attributes ALTER COLUMN data_source_attribute_id SET DEFAULT nextval('public.data_source_attributes_data_source_attribute_id_seq'::regclass);


--
-- TOC entry 4004 (class 2604 OID 39743)
-- Name: data_source_types data_source_type_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.data_source_types ALTER COLUMN data_source_type_id SET DEFAULT nextval('public.data_source_types_data_source_type_id_seq'::regclass);


--
-- TOC entry 4026 (class 2604 OID 39975)
-- Name: data_sources data_source_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.data_sources ALTER COLUMN data_source_id SET DEFAULT nextval('public.data_sources_data_source_id_seq'::regclass);


--
-- TOC entry 3967 (class 2604 OID 39231)
-- Name: departments department_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.departments ALTER COLUMN department_id SET DEFAULT nextval('public.departments_department_id_seq'::regclass);


--
-- TOC entry 4066 (class 2604 OID 41087)
-- Name: downloaded_reports download_report_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.downloaded_reports ALTER COLUMN download_report_id SET DEFAULT nextval('public.downloaded_reports_download_report_id_seq'::regclass);


--
-- TOC entry 3979 (class 2604 OID 39337)
-- Name: equipment equipment_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.equipment ALTER COLUMN equipment_id SET DEFAULT nextval('public.equipment_equipment_id_seq'::regclass);


--
-- TOC entry 3969 (class 2604 OID 39247)
-- Name: equipment_types equipment_type_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.equipment_types ALTER COLUMN equipment_type_id SET DEFAULT nextval('public.equipment_types_equipment_type_id_seq'::regclass);


--
-- TOC entry 3958 (class 2604 OID 39133)
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- TOC entry 3966 (class 2604 OID 39222)
-- Name: frequencies frequency_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.frequencies ALTER COLUMN frequency_id SET DEFAULT nextval('public.frequencies_frequency_id_seq'::regclass);


--
-- TOC entry 4006 (class 2604 OID 39759)
-- Name: functionals functional_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.functionals ALTER COLUMN functional_id SET DEFAULT nextval('public.functionals_functional_id_seq'::regclass);


--
-- TOC entry 3957 (class 2604 OID 39116)
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- TOC entry 4034 (class 2604 OID 40101)
-- Name: list_parameters list_parameter_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.list_parameters ALTER COLUMN list_parameter_id SET DEFAULT nextval('public.list_parameters_list_parameter_id_seq'::regclass);


--
-- TOC entry 3956 (class 2604 OID 39095)
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- TOC entry 3976 (class 2604 OID 39297)
-- Name: modules module_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.modules ALTER COLUMN module_id SET DEFAULT nextval('public.modules_module_id_seq'::regclass);


--
-- TOC entry 3965 (class 2604 OID 39210)
-- Name: otps otp_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.otps ALTER COLUMN otp_id SET DEFAULT nextval('public.otps_otp_id_seq'::regclass);


--
-- TOC entry 3964 (class 2604 OID 39192)
-- Name: password_reset_tokens id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.password_reset_tokens ALTER COLUMN id SET DEFAULT nextval('public.password_reset_tokens_id_seq'::regclass);


--
-- TOC entry 3975 (class 2604 OID 39285)
-- Name: personal_access_tokens id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal_access_tokens ALTER COLUMN id SET DEFAULT nextval('public.personal_access_tokens_id_seq'::regclass);


--
-- TOC entry 3961 (class 2604 OID 39152)
-- Name: plants plant_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.plants ALTER COLUMN plant_id SET DEFAULT nextval('public.plants_plant_id_seq'::regclass);


--
-- TOC entry 3985 (class 2604 OID 39436)
-- Name: reasons reason_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reasons ALTER COLUMN reason_id SET DEFAULT nextval('public.reasons_reason_id_seq'::regclass);


--
-- TOC entry 3978 (class 2604 OID 39320)
-- Name: role_abilities role_ability_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.role_abilities ALTER COLUMN role_ability_id SET DEFAULT nextval('public.role_abilities_role_ability_id_seq'::regclass);


--
-- TOC entry 3962 (class 2604 OID 39164)
-- Name: roles role_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles ALTER COLUMN role_id SET DEFAULT nextval('public.roles_role_id_seq'::regclass);


--
-- TOC entry 3968 (class 2604 OID 39240)
-- Name: sections section_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sections ALTER COLUMN section_id SET DEFAULT nextval('public.sections_section_id_seq'::regclass);


--
-- TOC entry 4002 (class 2604 OID 39679)
-- Name: service_asset_types service_asset_type_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.service_asset_types ALTER COLUMN service_asset_type_id SET DEFAULT nextval('public.service_asset_types_service_asset_type_id_seq'::regclass);


--
-- TOC entry 4022 (class 2604 OID 39912)
-- Name: service_attribute_types service_attribute_type_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.service_attribute_types ALTER COLUMN service_attribute_type_id SET DEFAULT nextval('public.service_attribute_types_service_attribute_type_id_seq'::regclass);


--
-- TOC entry 4030 (class 2604 OID 40033)
-- Name: service_attribute_values service_attribute_value_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.service_attribute_values ALTER COLUMN service_attribute_value_id SET DEFAULT nextval('public.service_attribute_values_service_attribute_value_id_seq'::regclass);


--
-- TOC entry 4015 (class 2604 OID 39831)
-- Name: service_attributes service_attribute_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.service_attributes ALTER COLUMN service_attribute_id SET DEFAULT nextval('public.service_attributes_service_attribute_id_seq'::regclass);


--
-- TOC entry 3972 (class 2604 OID 39268)
-- Name: service_type service_type_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.service_type ALTER COLUMN service_type_id SET DEFAULT nextval('public.service_type_service_type_id_seq'::regclass);


--
-- TOC entry 3981 (class 2604 OID 39368)
-- Name: services service_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.services ALTER COLUMN service_id SET DEFAULT nextval('public.services_service_id_seq'::regclass);


--
-- TOC entry 4001 (class 2604 OID 39662)
-- Name: spare_asset_types spare_asset_type_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.spare_asset_types ALTER COLUMN spare_asset_type_id SET DEFAULT nextval('public.spare_asset_types_spare_asset_type_id_seq'::regclass);


--
-- TOC entry 4019 (class 2604 OID 39861)
-- Name: spare_attribute_types spare_attribute_type_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.spare_attribute_types ALTER COLUMN spare_attribute_type_id SET DEFAULT nextval('public.spare_attribute_types_spare_attribute_type_id_seq'::regclass);


--
-- TOC entry 4029 (class 2604 OID 40016)
-- Name: spare_attribute_values spare_attribute_value_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.spare_attribute_values ALTER COLUMN spare_attribute_value_id SET DEFAULT nextval('public.spare_attribute_values_spare_attribute_value_id_seq'::regclass);


--
-- TOC entry 4009 (class 2604 OID 39786)
-- Name: spare_attributes spare_attribute_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.spare_attributes ALTER COLUMN spare_attribute_id SET DEFAULT nextval('public.spare_attributes_spare_attribute_id_seq'::regclass);


--
-- TOC entry 3971 (class 2604 OID 39261)
-- Name: spare_types spare_type_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.spare_types ALTER COLUMN spare_type_id SET DEFAULT nextval('public.spare_types_spare_type_id_seq'::regclass);


--
-- TOC entry 3980 (class 2604 OID 39356)
-- Name: spares spare_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.spares ALTER COLUMN spare_id SET DEFAULT nextval('public.spares_spare_id_seq'::regclass);


--
-- TOC entry 4055 (class 2604 OID 40719)
-- Name: template_attribute_values template_attribute_value_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.template_attribute_values ALTER COLUMN template_attribute_value_id SET DEFAULT nextval('public.template_attribute_values_template_attribute_value_id_seq'::regclass);


--
-- TOC entry 4064 (class 2604 OID 41012)
-- Name: template_datasource_values template_datasource_value_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.template_datasource_values ALTER COLUMN template_datasource_value_id SET DEFAULT nextval('public.template_datasource_values_template_datasource_value_id_seq'::regclass);


--
-- TOC entry 4054 (class 2604 OID 40702)
-- Name: template_departments template_department_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.template_departments ALTER COLUMN template_department_id SET DEFAULT nextval('public.template_departments_template_department_id_seq'::regclass);


--
-- TOC entry 4060 (class 2604 OID 40874)
-- Name: template_service_values template_service_value_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.template_service_values ALTER COLUMN template_service_value_id SET DEFAULT nextval('public.template_service_values_template_service_value_id_seq'::regclass);


--
-- TOC entry 4057 (class 2604 OID 40773)
-- Name: template_spare_values template_spare_value_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.template_spare_values ALTER COLUMN template_spare_value_id SET DEFAULT nextval('public.template_spare_values_template_spare_value_id_seq'::regclass);


--
-- TOC entry 4062 (class 2604 OID 40943)
-- Name: template_variable_values template_variable_value_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.template_variable_values ALTER COLUMN template_variable_value_id SET DEFAULT nextval('public.template_variable_values_template_variable_value_id_seq'::regclass);


--
-- TOC entry 4053 (class 2604 OID 40689)
-- Name: template_zones template_zone_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.template_zones ALTER COLUMN template_zone_id SET DEFAULT nextval('public.template_zones_template_zone_id_seq'::regclass);


--
-- TOC entry 3986 (class 2604 OID 39443)
-- Name: user_activities user_activity_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_activities ALTER COLUMN user_activity_id SET DEFAULT nextval('public.user_activities_user_activity_id_seq'::regclass);


--
-- TOC entry 3991 (class 2604 OID 39542)
-- Name: user_asset_checks user_asset_check_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_asset_checks ALTER COLUMN user_asset_check_id SET DEFAULT nextval('public.user_asset_checks_user_asset_check_id_seq'::regclass);


--
-- TOC entry 4042 (class 2604 OID 40400)
-- Name: user_asset_variables user_asset_variable_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_asset_variables ALTER COLUMN user_asset_variable_id SET DEFAULT nextval('public.user_asset_variables_user_asset_variable_id_seq'::regclass);


--
-- TOC entry 3994 (class 2604 OID 39567)
-- Name: user_check_attachments user_check_attachment_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_check_attachments ALTER COLUMN user_check_attachment_id SET DEFAULT nextval('public.user_check_attachments_user_check_attachment_id_seq'::regclass);


--
-- TOC entry 3990 (class 2604 OID 39518)
-- Name: user_checks user_check_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_checks ALTER COLUMN user_check_id SET DEFAULT nextval('public.user_checks_user_check_id_seq'::regclass);


--
-- TOC entry 3987 (class 2604 OID 39472)
-- Name: user_services user_service_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_services ALTER COLUMN user_service_id SET DEFAULT nextval('public.user_services_user_service_id_seq'::regclass);


--
-- TOC entry 3989 (class 2604 OID 39501)
-- Name: user_spares user_spare_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_spares ALTER COLUMN user_spare_id SET DEFAULT nextval('public.user_spares_user_spare_id_seq'::regclass);


--
-- TOC entry 4041 (class 2604 OID 40371)
-- Name: user_variables user_variable_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_variables ALTER COLUMN user_variable_id SET DEFAULT nextval('public.user_variables_user_variable_id_seq'::regclass);


--
-- TOC entry 3963 (class 2604 OID 39173)
-- Name: users user_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN user_id SET DEFAULT nextval('public.users_user_id_seq'::regclass);


--
-- TOC entry 4025 (class 2604 OID 39958)
-- Name: variable_asset_types variable_asset_type_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.variable_asset_types ALTER COLUMN variable_asset_type_id SET DEFAULT nextval('public.variable_asset_types_variable_asset_type_id_seq'::regclass);


--
-- TOC entry 4021 (class 2604 OID 39895)
-- Name: variable_attribute_types variable_attribute_type_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.variable_attribute_types ALTER COLUMN variable_attribute_type_id SET DEFAULT nextval('public.variable_attribute_types_variable_attribute_type_id_seq'::regclass);


--
-- TOC entry 4031 (class 2604 OID 40050)
-- Name: variable_attribute_values variable_attribute_value_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.variable_attribute_values ALTER COLUMN variable_attribute_value_id SET DEFAULT nextval('public.variable_attribute_values_variable_attribute_value_id_seq'::regclass);


--
-- TOC entry 4013 (class 2604 OID 39816)
-- Name: variable_attributes variable_attribute_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.variable_attributes ALTER COLUMN variable_attribute_id SET DEFAULT nextval('public.variable_attributes_variable_attribute_id_seq'::regclass);


--
-- TOC entry 4008 (class 2604 OID 39777)
-- Name: variable_types variable_type_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.variable_types ALTER COLUMN variable_type_id SET DEFAULT nextval('public.variable_types_variable_type_id_seq'::regclass);


--
-- TOC entry 4024 (class 2604 OID 39946)
-- Name: variables variable_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.variables ALTER COLUMN variable_id SET DEFAULT nextval('public.variables_variable_id_seq'::regclass);


--
-- TOC entry 4723 (class 0 OID 39303)
-- Dependencies: 260
-- Data for Name: abilities; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.abilities (ability_id, ability, description, module_id, created_at, updated_at) FROM stdin;
1	roles.view	View Roles	1	2025-01-28 18:06:16	2025-01-28 18:06:16
2	roles.create	Create Roles	1	2025-01-28 18:06:16	2025-01-28 18:06:16
3	roles.update	Edit Roles	1	2025-01-28 18:06:16	2025-01-28 18:06:16
4	roles.delete	Delete Roles	1	2025-01-28 18:06:16	2025-01-28 18:06:16
5	users.view	View Users	2	2025-01-28 18:06:16	2025-01-28 18:06:16
6	users.create	Create Users	2	2025-01-28 18:06:16	2025-01-28 18:06:16
7	users.update	Edit Users	2	2025-01-28 18:06:16	2025-01-28 18:06:16
8	users.delete	Delete Users	2	2025-01-28 18:06:16	2025-01-28 18:06:16
9	clusters.view	View Clusters	3	2025-01-28 18:06:17	2025-01-28 18:06:17
10	clusters.create	Create Clusters	3	2025-01-28 18:06:17	2025-01-28 18:06:17
11	clusters.update	Edit Clusters	3	2025-01-28 18:06:17	2025-01-28 18:06:17
12	clusters.delete	Delete Clusters	3	2025-01-28 18:06:17	2025-01-28 18:06:17
13	plants.view	View Plants	4	2025-01-28 18:06:17	2025-01-28 18:06:17
14	plants.create	Create Plants	4	2025-01-28 18:06:17	2025-01-28 18:06:17
15	plants.update	Edit Plants	4	2025-01-28 18:06:17	2025-01-28 18:06:17
16	plants.delete	Delete Plants	4	2025-01-28 18:06:17	2025-01-28 18:06:17
17	sections.view	View Sections	5	2025-01-28 18:06:17	2025-01-28 18:06:17
18	sections.create	Create Sections	5	2025-01-28 18:06:17	2025-01-28 18:06:17
19	sections.update	Edit Sections	5	2025-01-28 18:06:17	2025-01-28 18:06:17
20	sections.delete	Delete Sections	5	2025-01-28 18:06:17	2025-01-28 18:06:17
21	equipment_types.view	View EquipmentTypes	6	2025-01-28 18:06:17	2025-01-28 18:06:17
22	equipment_types.create	Create EquipmentTypes	6	2025-01-28 18:06:17	2025-01-28 18:06:17
23	equipment_types.update	Edit EquipmentTypes	6	2025-01-28 18:06:17	2025-01-28 18:06:17
24	equipment_types.delete	Delete EquipmentTypes	6	2025-01-28 18:06:17	2025-01-28 18:06:17
25	asset_types.view	View AssetTypes	7	2025-01-28 18:06:17	2025-01-28 18:06:17
26	asset_types.create	Create AssetTypes	7	2025-01-28 18:06:17	2025-01-28 18:06:17
27	asset_types.update	Edit AssetTypes	7	2025-01-28 18:06:17	2025-01-28 18:06:17
28	asset_types.delete	Delete AssetTypes	7	2025-01-28 18:06:17	2025-01-28 18:06:17
29	spare_types.view	View SpareTypes	8	2025-01-28 18:06:17	2025-01-28 18:06:17
30	spare_types.create	Create SpareTypes	8	2025-01-28 18:06:17	2025-01-28 18:06:17
31	spare_types.update	Edit SpareTypes	8	2025-01-28 18:06:17	2025-01-28 18:06:17
32	spare_types.delete	Delete SpareTypes	8	2025-01-28 18:06:17	2025-01-28 18:06:17
33	service_types.view	View ServiceTypes	9	2025-01-28 18:06:17	2025-01-28 18:06:17
34	service_types.create	Create ServiceTypes	9	2025-01-28 18:06:17	2025-01-28 18:06:17
35	service_types.update	Edit ServiceTypes	9	2025-01-28 18:06:17	2025-01-28 18:06:17
36	service_types.delete	Delete ServiceTypes	9	2025-01-28 18:06:17	2025-01-28 18:06:17
37	voltages.view	View Voltages	10	2025-01-28 18:06:17	2025-01-28 18:06:17
38	voltages.create	Create Voltages	10	2025-01-28 18:06:17	2025-01-28 18:06:17
39	voltages.update	Edit Voltages	10	2025-01-28 18:06:17	2025-01-28 18:06:17
40	voltages.delete	Delete Voltages	10	2025-01-28 18:06:17	2025-01-28 18:06:17
41	watt_ratings.view	View WattRatings	11	2025-01-28 18:06:17	2025-01-28 18:06:17
42	watt_ratings.create	Create WattRatings	11	2025-01-28 18:06:17	2025-01-28 18:06:17
43	watt_ratings.update	Edit WattRatings	11	2025-01-28 18:06:17	2025-01-28 18:06:17
44	watt_ratings.delete	Delete WattRatings	11	2025-01-28 18:06:17	2025-01-28 18:06:17
45	frames.view	View Frames	12	2025-01-28 18:06:17	2025-01-28 18:06:17
46	frames.create	Create Frames	12	2025-01-28 18:06:17	2025-01-28 18:06:17
47	frames.update	Edit Frames	12	2025-01-28 18:06:17	2025-01-28 18:06:17
48	frames.delete	Delete Frames	12	2025-01-28 18:06:17	2025-01-28 18:06:17
49	mountings.view	View Mountings	13	2025-01-28 18:06:17	2025-01-28 18:06:17
50	mountings.create	Create Mountings	13	2025-01-28 18:06:17	2025-01-28 18:06:17
51	mountings.update	Edit Mountings	13	2025-01-28 18:06:17	2025-01-28 18:06:17
52	mountings.delete	Delete Mountings	13	2025-01-28 18:06:17	2025-01-28 18:06:17
53	makes.view	View Makes	14	2025-01-28 18:06:17	2025-01-28 18:06:17
54	makes.create	Create Makes	14	2025-01-28 18:06:17	2025-01-28 18:06:17
55	makes.update	Edit Makes	14	2025-01-28 18:06:17	2025-01-28 18:06:17
56	makes.delete	Delete Makes	14	2025-01-28 18:06:17	2025-01-28 18:06:17
57	speeds.view	View Speeds	15	2025-01-28 18:06:17	2025-01-28 18:06:17
58	speeds.create	Create Speeds	15	2025-01-28 18:06:17	2025-01-28 18:06:17
59	speeds.update	Edit Speeds	15	2025-01-28 18:06:17	2025-01-28 18:06:17
60	speeds.delete	Delete Speeds	15	2025-01-28 18:06:17	2025-01-28 18:06:17
61	checks.view	View Checks	16	2025-01-28 18:06:17	2025-01-28 18:06:17
62	checks.create	Create Checks	16	2025-01-28 18:06:17	2025-01-28 18:06:17
63	checks.update	Edit Checks	16	2025-01-28 18:06:17	2025-01-28 18:06:17
64	checks.delete	Delete Checks	16	2025-01-28 18:06:17	2025-01-28 18:06:17
65	equipment.view	View Equipment	17	2025-01-28 18:06:17	2025-01-28 18:06:17
66	equipment.create	Create Equipment	17	2025-01-28 18:06:17	2025-01-28 18:06:17
67	equipment.update	Edit Equipment	17	2025-01-28 18:06:17	2025-01-28 18:06:17
68	equipment.delete	Delete Equipment	17	2025-01-28 18:06:17	2025-01-28 18:06:17
69	spares.view	View Spares	18	2025-01-28 18:06:17	2025-01-28 18:06:17
70	spares.create	Create Spares	18	2025-01-28 18:06:17	2025-01-28 18:06:17
71	spares.update	Edit Spares	18	2025-01-28 18:06:17	2025-01-28 18:06:17
72	spares.delete	Delete Spares	18	2025-01-28 18:06:17	2025-01-28 18:06:17
73	services.view	View Services	19	2025-01-28 18:06:17	2025-01-28 18:06:17
74	services.create	Create Services	19	2025-01-28 18:06:17	2025-01-28 18:06:17
75	services.update	Edit Services	19	2025-01-28 18:06:17	2025-01-28 18:06:17
76	services.delete	Delete Services	19	2025-01-28 18:06:17	2025-01-28 18:06:17
77	assets.view	View Assets	20	2025-01-28 18:06:17	2025-01-28 18:06:17
78	assets.create	Create Assets	20	2025-01-28 18:06:17	2025-01-28 18:06:17
79	assets.update	Edit Assets	20	2025-01-28 18:06:17	2025-01-28 18:06:17
80	assets.delete	Delete Assets	20	2025-01-28 18:06:17	2025-01-28 18:06:17
81	assetParameters.view	View AssetParameters	21	2025-01-28 18:06:17	2025-01-28 18:06:17
82	assetParameters.create	Create AssetParameters	21	2025-01-28 18:06:17	2025-01-28 18:06:17
83	assetParameters.update	Edit AssetParameters	21	2025-01-28 18:06:17	2025-01-28 18:06:17
84	assetParameters.delete	Delete AssetParameters	21	2025-01-28 18:06:17	2025-01-28 18:06:17
85	assetviews.view	View AssetViews	22	2025-01-28 18:06:17	2025-01-28 18:06:17
86	assetSpares.view	View AssetSpares	23	2025-01-28 18:06:17	2025-01-28 18:06:17
87	assetSpares.create	Create AssetSpares	23	2025-01-28 18:06:17	2025-01-28 18:06:17
88	assetSpares.delete	Delete AssetSpares	23	2025-01-28 18:06:17	2025-01-28 18:06:17
89	assetChecks.view	View AssetChecks	24	2025-01-28 18:06:17	2025-01-28 18:06:17
90	assetChecks.create	Create AssetChecks	24	2025-01-28 18:06:17	2025-01-28 18:06:17
91	assetChecks.delete	Delete AssetChecks	24	2025-01-28 18:06:17	2025-01-28 18:06:17
92	reasons.view	View Reasons	25	2025-01-28 18:06:17	2025-01-28 18:06:17
93	reasons.create	Create Reasons	25	2025-01-28 18:06:17	2025-01-28 18:06:17
94	reasons.update	Edit Reasons	25	2025-01-28 18:06:17	2025-01-28 18:06:17
95	reasons.delete	Delete Reasons	25	2025-01-28 18:06:17	2025-01-28 18:06:17
96	userActivities.view	View UserActivities	26	2025-01-28 18:06:17	2025-01-28 18:06:17
97	userActivities.create	Create UserActivities	26	2025-01-28 18:06:17	2025-01-28 18:06:17
98	userActivities.update	Edit UserActivities	26	2025-01-28 18:06:17	2025-01-28 18:06:17
99	userActivities.delete	Delete UserActivities	26	2025-01-28 18:06:17	2025-01-28 18:06:17
100	userServices.view	View UserServices	27	2025-01-28 18:06:17	2025-01-28 18:06:17
101	userServices.create	Create UserServices	27	2025-01-28 18:06:17	2025-01-28 18:06:17
102	userServices.update	Edit UserServices	27	2025-01-28 18:06:17	2025-01-28 18:06:17
103	userServices.delete	Delete UserServices	27	2025-01-28 18:06:17	2025-01-28 18:06:17
104	userChecks.view	View UserChecks	28	2025-01-28 18:06:17	2025-01-28 18:06:17
105	userChecks.create	Create UserChecks	28	2025-01-28 18:06:17	2025-01-28 18:06:17
106	userChecks.update	Edit UserChecks	28	2025-01-28 18:06:17	2025-01-28 18:06:17
107	userChecks.delete	Delete UserChecks	28	2025-01-28 18:06:17	2025-01-28 18:06:17
108	permissions.view	Update Permissions	29	2025-01-28 18:06:17	2025-01-28 18:06:17
\.


--
-- TOC entry 4775 (class 0 OID 39765)
-- Dependencies: 312
-- Data for Name: accessory_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.accessory_types (accessory_type_id, accessory_type_code, accessory_type_name, created_at, updated_at, deleted_at) FROM stdin;
1	LIST	Item List	2025-01-28 18:46:57	2025-01-28 18:46:57	\N
2	MANUAL	User Manual	2025-01-28 18:47:07	2025-01-28 18:47:07	\N
3	DOC	Document Type	2025-01-28 18:47:19	2025-01-28 18:47:19	\N
4	IMAGE	Image Type	2025-01-28 18:47:33	2025-01-28 18:47:33	\N
\.


--
-- TOC entry 4839 (class 0 OID 40463)
-- Dependencies: 376
-- Data for Name: activity_attribute_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.activity_attribute_types (activity_attribute_type_id, activity_attribute_id, reason_id, created_at, updated_at, deleted_at) FROM stdin;
\.


--
-- TOC entry 4843 (class 0 OID 40497)
-- Dependencies: 380
-- Data for Name: activity_attribute_values; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.activity_attribute_values (activity_attribute_value_id, user_activity_id, activity_attribute_id, field_value, created_at, updated_at, deleted_at) FROM stdin;
\.


--
-- TOC entry 4837 (class 0 OID 40443)
-- Dependencies: 374
-- Data for Name: activity_attributes; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.activity_attributes (activity_attribute_id, field_name, display_name, field_type, field_values, field_length, is_required, user_id, list_parameter_id, created_at, updated_at, deleted_at) FROM stdin;
\.


--
-- TOC entry 4690 (class 0 OID 39142)
-- Dependencies: 227
-- Data for Name: areas; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.areas (area_id, area_code, area_name, created_at, updated_at, deleted_at) FROM stdin;
2	Coke Oven	Coke Oven	2025-01-28 18:12:43	2025-01-28 18:12:43	\N
3	Agglomaration	Agglomaration	2025-01-28 18:12:49	2025-01-28 18:12:49	\N
4	Iron Zone	Iron Zone	2025-01-28 18:12:59	2025-01-28 18:12:59	\N
5	Steel Zone 1	Steel Zone 1	2025-01-28 18:13:22	2025-01-28 18:13:22	\N
6	Steel Zone 2	Steel Zone 2	2025-01-28 18:13:53	2025-01-28 18:13:53	\N
1	DOLVI	DOLVI	\N	2025-01-28 18:14:07	\N
\.


--
-- TOC entry 4831 (class 0 OID 40319)
-- Dependencies: 368
-- Data for Name: asset_accessories; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.asset_accessories (asset_accessory_id, area_id, plant_id, asset_id, asset_zone_id, accessory_type_id, accessory_name, attachment, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 4757 (class 0 OID 39608)
-- Dependencies: 294
-- Data for Name: asset_attribute_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.asset_attribute_types (asset_attribute_type_id, asset_attribute_id, asset_type_id, created_at, updated_at, deleted_at) FROM stdin;
1	1	1	2025-01-28 18:50:20	2025-01-28 18:50:20	\N
2	2	32	2025-01-28 18:51:59	2025-01-28 18:51:59	\N
3	2	29	2025-01-28 18:51:59	2025-01-28 18:51:59	\N
4	3	32	2025-01-28 18:54:51	2025-01-28 18:54:51	\N
5	3	29	2025-01-28 18:54:51	2025-01-28 18:54:51	\N
6	4	50	2025-01-28 18:57:44	2025-01-28 18:57:44	\N
7	4	49	2025-01-28 18:57:44	2025-01-28 18:57:44	\N
8	4	32	2025-01-28 18:57:44	2025-01-28 18:57:44	\N
9	5	50	2025-01-28 18:58:33	2025-01-28 18:58:33	\N
10	5	49	2025-01-28 18:58:33	2025-01-28 18:58:33	\N
11	5	32	2025-01-28 18:58:33	2025-01-28 18:58:33	\N
12	6	50	2025-01-28 19:00:22	2025-01-28 19:00:22	\N
13	6	49	2025-01-28 19:00:22	2025-01-28 19:00:22	\N
14	6	32	2025-01-28 19:00:22	2025-01-28 19:00:22	\N
15	7	50	2025-01-28 19:01:15	2025-01-28 19:01:15	\N
16	7	49	2025-01-28 19:01:15	2025-01-28 19:01:15	\N
17	7	32	2025-01-28 19:01:15	2025-01-28 19:01:15	\N
18	8	50	2025-01-28 19:02:06	2025-01-28 19:02:06	\N
19	8	49	2025-01-28 19:02:06	2025-01-28 19:02:06	\N
20	8	32	2025-01-28 19:02:06	2025-01-28 19:02:06	\N
21	9	50	2025-01-28 19:03:03	2025-01-28 19:03:03	\N
22	9	49	2025-01-28 19:03:03	2025-01-28 19:03:03	\N
23	9	32	2025-01-28 19:03:03	2025-01-28 19:03:03	\N
24	10	50	2025-01-28 19:03:47	2025-01-28 19:03:47	\N
25	10	49	2025-01-28 19:03:47	2025-01-28 19:03:47	\N
26	10	32	2025-01-28 19:03:47	2025-01-28 19:03:47	\N
\.


--
-- TOC entry 4759 (class 0 OID 39625)
-- Dependencies: 296
-- Data for Name: asset_attribute_values; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.asset_attribute_values (asset_attribute_value_id, asset_id, asset_attribute_id, field_value, created_at, updated_at, deleted_at) FROM stdin;
1	1	1	10000	2025-01-28 21:13:37	2025-01-28 21:13:37	\N
2	2	4	DIMAG	2025-01-28 21:17:10	2025-01-28 21:17:10	\N
3	2	5	5	2025-01-28 21:17:10	2025-01-28 21:17:10	\N
4	2	6	Trunnion	2025-01-28 21:17:10	2025-01-28 21:17:10	\N
5	2	7	188	2025-01-28 21:17:10	2025-01-28 21:17:10	\N
6	2	8	CONARCH	2025-01-28 21:17:10	2025-01-28 21:17:10	\N
7	2	9	SMS :: CONARC#5	2025-01-28 21:17:10	2025-01-28 21:17:10	\N
8	2	10	CONARC#5	2025-01-28 21:17:10	2025-01-28 21:17:10	\N
9	3	2	STL4	2025-01-28 21:20:42	2025-01-28 21:20:42	\N
10	3	3		2025-01-28 21:20:42	2025-01-28 21:20:42	\N
11	3	4	DIMAG	2025-01-28 21:20:42	2025-01-28 21:20:42	\N
12	3	5	5	2025-01-28 21:20:42	2025-01-28 21:20:42	\N
13	3	6	BellArm	2025-01-28 21:20:42	2025-01-28 21:20:42	\N
14	3	7	188	2025-01-28 21:20:42	2025-01-28 21:20:42	\N
15	3	8	Ladle	2025-01-28 21:20:42	2025-01-28 21:20:42	\N
16	3	9	SMS :: Steel Ladle STL#4	2025-01-28 21:20:42	2025-01-28 21:20:42	\N
17	3	10	STL#4	2025-01-28 21:20:42	2025-01-28 21:20:42	\N
18	4	4	DIMAG	2025-01-28 21:23:06	2025-01-28 21:23:06	\N
19	4	5	10	2025-01-28 21:23:06	2025-01-28 21:23:06	\N
20	4	6	Trunion	2025-01-28 21:23:06	2025-01-28 21:23:06	\N
21	4	7	188	2025-01-28 21:23:06	2025-01-28 21:23:06	\N
22	4	8	Bottom Safety,Side wall safety,side wall brick, bottom working brick,Side lower bank and bottom with	2025-01-28 21:23:06	2025-01-28 21:23:06	\N
23	4	9	SMS :: CONARC#4	2025-01-28 21:23:06	2025-01-28 21:23:06	\N
24	4	10	CONARC04	2025-01-28 21:23:06	2025-01-28 21:23:06	\N
25	5	4	DIMAG	2025-01-28 21:25:32	2025-01-28 21:25:32	\N
26	5	5	5	2025-01-28 21:25:32	2025-01-28 21:25:32	\N
27	5	6	Trunion	2025-01-28 21:25:32	2025-01-28 21:25:32	\N
28	5	7	188	2025-01-28 21:25:32	2025-01-28 21:25:32	\N
29	5	8	Bottom Safety,Side wall safety,side wall brick, bottom working brick,Side lower bank and bottom with	2025-01-28 21:25:32	2025-01-28 21:25:32	\N
30	5	9	SMS :: CONARC#1	2025-01-28 21:25:32	2025-01-28 21:25:32	\N
31	5	10	CONARC01	2025-01-28 21:25:32	2025-01-28 21:25:32	\N
32	6	2	STL03	2025-01-28 21:28:28	2025-01-28 21:28:28	\N
33	6	3		2025-01-28 21:28:28	2025-01-28 21:28:28	\N
34	6	4	DIMAG	2025-01-28 21:28:28	2025-01-28 21:28:28	\N
35	6	5	25	2025-01-28 21:28:28	2025-01-28 21:28:28	\N
36	6	6	BellArm	2025-01-28 21:28:28	2025-01-28 21:28:28	\N
37	6	7	188	2025-01-28 21:28:28	2025-01-28 21:28:28	\N
38	6	8	Back up lining , Side Wall S/z ,M/z  &bottom, Slide gate , Porous Plug	2025-01-28 21:28:28	2025-01-28 21:28:28	\N
39	6	9	SMS :: Steel Ladle STL#3	2025-01-28 21:28:28	2025-01-28 21:28:28	\N
40	6	10	STL03	2025-01-28 21:28:28	2025-01-28 21:28:28	\N
41	7	2	STL01	2025-01-28 21:31:28	2025-01-28 21:31:28	\N
42	7	3		2025-01-28 21:31:28	2025-01-28 21:31:28	\N
43	7	4	DIMAG	2025-01-28 21:31:28	2025-01-28 21:31:28	\N
44	7	5	10	2025-01-28 21:31:28	2025-01-28 21:31:28	\N
45	7	6	BellArm	2025-01-28 21:31:28	2025-01-28 21:31:28	\N
46	7	7	188	2025-01-28 21:31:28	2025-01-28 21:31:28	\N
47	7	8	Back up lining , Side Wall S/z ,M/z  &bottom, Slide gate , Porous Plug	2025-01-28 21:31:28	2025-01-28 21:31:28	\N
48	7	9	SMS :: Steel Ladle STL#1	2025-01-28 21:31:28	2025-01-28 21:31:28	\N
49	7	10	STL01	2025-01-28 21:31:28	2025-01-28 21:31:28	\N
50	8	2	LDL0001	2025-01-28 21:34:05	2025-01-28 21:34:05	\N
51	8	3		2025-01-28 21:34:05	2025-01-28 21:34:05	\N
52	8	4	x	2025-01-28 21:34:05	2025-01-28 21:34:05	\N
53	8	5	5	2025-01-28 21:34:05	2025-01-28 21:34:05	\N
54	8	6	x	2025-01-28 21:34:05	2025-01-28 21:34:05	\N
55	8	7	188	2025-01-28 21:34:05	2025-01-28 21:34:05	\N
56	8	8	x	2025-01-28 21:34:05	2025-01-28 21:34:05	\N
57	8	9		2025-01-28 21:34:05	2025-01-28 21:34:05	\N
58	8	10	LDL01	2025-01-28 21:34:05	2025-01-28 21:34:05	\N
\.


--
-- TOC entry 4755 (class 0 OID 39593)
-- Dependencies: 292
-- Data for Name: asset_attributes; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.asset_attributes (asset_attribute_id, field_name, display_name, field_type, field_values, field_length, is_required, user_id, created_at, updated_at, deleted_at, list_parameter_id) FROM stdin;
1	Capacity(TPD)	Capacity	Number	\N	10	f	1	2025-01-28 18:50:20	2025-01-28 18:50:20	\N	\N
2	Equipment code (PPMS)	Code in PPMS	Text	\N	10	f	1	2025-01-28 18:51:59	2025-01-28 18:51:59	\N	\N
3	asset_vendor	Vendor Name	List	\N	10	f	1	2025-01-28 18:54:51	2025-01-28 18:54:51	\N	3
4	asset_make model	Equipment Make Model	Text	\N	20	f	1	2025-01-28 18:57:44	2025-01-28 18:57:44	\N	\N
5	asset_tagcount	Equipment Tag Count	Text	\N	20	t	1	2025-01-28 18:58:33	2025-01-28 18:58:33	\N	\N
6	asset_drive type	Equipment Drive type	Text	\N	20	t	1	2025-01-28 19:00:22	2025-01-28 19:00:22	\N	\N
7	asset_capacity	Equipment Capacity (Tons)	Number	\N	10	t	1	2025-01-28 19:01:15	2025-01-28 19:01:15	\N	\N
8	asset_subtype	Equipment Subtype	Text	\N	100	t	1	2025-01-28 19:02:06	2025-01-28 19:02:06	\N	\N
10	asset_sapcode	Equipment Code (SAP)	Text	\N	15	t	1	2025-01-28 19:03:47	2025-01-28 19:03:47	\N	\N
9	asset_description	Equipment Description	Text	\N	25	f	1	2025-01-28 19:03:03	2025-01-28 21:34:21	\N	\N
\.


--
-- TOC entry 4737 (class 0 OID 39411)
-- Dependencies: 274
-- Data for Name: asset_checks; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.asset_checks (asset_check_id, check_id, asset_id, plant_id, created_at, updated_at, deleted_at, lcl, ucl, default_value, area_id, asset_zone_id) FROM stdin;
1	15	2	17	2025-01-28 21:40:17	2025-01-28 21:40:17	\N	0	6	\N	5	4
2	16	2	17	2025-01-28 21:40:24	2025-01-28 21:40:24	\N	0	2	\N	5	4
3	17	2	17	2025-01-28 21:40:34	2025-01-28 21:40:34	\N	0	17	\N	5	4
4	18	2	17	2025-01-28 21:40:46	2025-01-28 21:40:46	\N	\N	\N	Ok	5	4
5	19	2	17	2025-01-28 21:40:57	2025-01-28 21:40:57	\N	\N	\N	Ok	5	5
6	23	3	17	2025-01-28 21:47:02	2025-01-28 21:47:02	\N	30	70	\N	5	6
7	24	3	17	2025-01-28 21:47:12	2025-01-28 21:47:12	\N	0	12	\N	5	6
8	25	3	17	2025-01-28 21:47:22	2025-01-28 21:47:22	\N	0.5	2.5	\N	5	6
9	26	3	17	2025-01-28 21:47:35	2025-01-28 21:47:35	\N	\N	\N	Ok	5	6
10	31	3	17	2025-01-28 21:47:53	2025-01-28 21:47:53	\N	\N	\N	GOOD	5	6
11	33	3	17	2025-01-28 21:48:04	2025-01-28 21:48:04	\N	40	80	\N	5	6
12	15	5	17	2025-01-28 21:56:35	2025-01-28 21:56:35	\N	0	6	\N	5	11
13	16	5	17	2025-01-28 21:56:43	2025-01-28 21:56:43	\N	0	2	\N	5	11
14	17	5	17	2025-01-28 21:56:56	2025-01-28 21:56:56	\N	0	17	\N	5	11
15	18	5	17	2025-01-28 21:57:05	2025-01-28 21:57:05	\N	\N	\N	Ok	5	11
16	19	5	17	2025-01-28 21:57:15	2025-01-28 21:57:15	\N	\N	\N	Ok	5	11
17	23	6	17	2025-01-28 22:00:58	2025-01-28 22:00:58	\N	30	70	\N	5	13
18	24	6	17	2025-01-28 22:01:08	2025-01-28 22:01:08	\N	0	12	\N	5	13
19	25	6	17	2025-01-28 22:01:19	2025-01-28 22:01:19	\N	0.5	2.5	\N	5	13
20	26	6	17	2025-01-28 22:01:30	2025-01-28 22:01:30	\N	\N	\N	Ok	5	13
21	33	6	17	2025-01-28 22:01:43	2025-01-28 22:01:43	\N	40	80	\N	5	13
22	23	7	17	2025-01-28 22:06:14	2025-01-28 22:06:14	\N	30	70	\N	5	16
23	24	7	17	2025-01-28 22:06:24	2025-01-28 22:06:24	\N	0	12	\N	5	16
24	25	7	17	2025-01-28 22:06:33	2025-01-28 22:06:33	\N	0.5	2.5	\N	5	16
25	26	7	17	2025-01-28 22:06:42	2025-01-28 22:06:42	\N	\N	\N	Ok	5	16
26	33	7	17	2025-01-28 22:06:52	2025-01-28 22:06:52	\N	40	80	\N	5	16
29	29	8	17	2025-01-28 22:54:10	2025-01-28 22:54:10	\N	1	5	\N	5	19
30	30	8	17	2025-01-28 22:54:24	2025-01-28 22:54:24	\N	\N	\N	GOOD	5	19
31	31	8	17	2025-01-28 22:54:37	2025-01-28 22:54:37	\N	\N	\N	GOOD	5	19
27	27	8	17	2025-01-28 22:53:39	2025-01-28 22:55:09	\N	300	500	\N	5	20
28	28	8	17	2025-01-28 22:53:54	2025-01-28 22:55:25	\N	300	500	\N	5	19
\.


--
-- TOC entry 4851 (class 0 OID 40615)
-- Dependencies: 388
-- Data for Name: asset_data_source_values; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.asset_data_source_values (asset_data_source_value_id, asset_data_source_id, asset_id, data_source_id, asset_zone_id, data_source_attribute_id, field_value, created_at, updated_at) FROM stdin;
1	1	2	1	4	1	15	2025-01-28 21:42:56	2025-01-28 21:42:56
2	1	2	1	4	2	xxx	2025-01-28 21:42:56	2025-01-28 21:42:56
3	1	2	1	4	3	1.1.1.1	2025-01-28 21:42:56	2025-01-28 21:42:56
4	1	2	1	4	4	SFTP	2025-01-28 21:42:56	2025-01-28 21:42:56
5	2	3	2	6	1	60	2025-01-28 21:50:17	2025-01-28 21:50:17
6	2	3	2	6	2	xxxxxx	2025-01-28 21:50:17	2025-01-28 21:50:17
7	2	3	2	6	3	1.1.1.1	2025-01-28 21:50:17	2025-01-28 21:50:17
8	2	3	2	6	4	SFTP	2025-01-28 21:50:17	2025-01-28 21:50:17
9	3	4	1	9	1	15	2025-01-28 21:52:22	2025-01-28 21:52:22
10	3	4	1	9	2	xxx	2025-01-28 21:52:22	2025-01-28 21:52:22
11	3	4	1	9	3	1.1.1.1	2025-01-28 21:52:22	2025-01-28 21:52:22
12	3	4	1	9	4	SFTP	2025-01-28 21:52:22	2025-01-28 21:52:22
13	4	6	2	13	1	60	2025-01-28 22:03:13	2025-01-28 22:03:13
14	4	6	2	13	2	xxxxxx	2025-01-28 22:03:13	2025-01-28 22:03:13
15	4	6	2	13	3	1.1.1.1	2025-01-28 22:03:13	2025-01-28 22:03:13
16	4	6	2	13	4	SFTP	2025-01-28 22:03:13	2025-01-28 22:03:13
17	5	7	2	16	1	60	2025-01-28 22:08:00	2025-01-28 22:08:00
18	5	7	2	16	2	xxxxxx	2025-01-28 22:08:00	2025-01-28 22:08:00
19	5	7	2	16	3	1.1.1.1	2025-01-28 22:08:00	2025-01-28 22:08:00
20	5	7	2	16	4	SFTP	2025-01-28 22:08:00	2025-01-28 22:08:00
21	6	8	2	19	1	60	2025-01-28 22:57:26	2025-01-28 22:57:26
22	6	8	2	19	2	xxxxxx	2025-01-28 22:57:26	2025-01-28 22:57:26
23	6	8	2	19	3	1.1.1.1	2025-01-28 22:57:26	2025-01-28 22:57:26
24	6	8	2	19	4	SFTP	2025-01-28 22:57:26	2025-01-28 22:57:26
\.


--
-- TOC entry 4829 (class 0 OID 40276)
-- Dependencies: 366
-- Data for Name: asset_data_sources; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.asset_data_sources (asset_data_source_id, area_id, plant_id, asset_id, asset_zone_id, data_source_type_id, data_source_id, created_at, updated_at, script) FROM stdin;
1	5	17	2	4	7	1	2025-01-28 21:42:56	2025-01-28 21:42:56	Torpedo Scanner
2	5	17	3	6	7	2	2025-01-28 21:50:17	2025-01-28 21:50:17	Ladle Scanner
3	5	17	4	9	7	1	2025-01-28 21:52:22	2025-01-28 21:52:22	Torpedo Scanner
4	5	17	6	13	7	2	2025-01-28 22:03:13	2025-01-28 22:03:13	Ladle Scanner
5	5	17	7	16	7	2	2025-01-28 22:08:00	2025-01-28 22:08:00	Ladle Scanner
6	5	17	8	19	7	2	2025-01-28 22:57:26	2025-01-28 22:57:26	Ladle Scanner
\.


--
-- TOC entry 4841 (class 0 OID 40480)
-- Dependencies: 378
-- Data for Name: asset_departments; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.asset_departments (asset_department_id, asset_id, department_id, created_at, updated_at) FROM stdin;
1	1	4	2025-01-28 21:13:37	2025-01-28 21:13:37
2	2	5	2025-01-28 21:17:10	2025-01-28 21:17:10
3	2	9	2025-01-28 21:17:10	2025-01-28 21:17:10
4	2	3	2025-01-28 21:17:10	2025-01-28 21:17:10
5	2	1	2025-01-28 21:17:10	2025-01-28 21:17:10
6	3	1	2025-01-28 21:20:42	2025-01-28 21:20:42
7	3	3	2025-01-28 21:20:42	2025-01-28 21:20:42
8	3	5	2025-01-28 21:20:42	2025-01-28 21:20:42
9	3	9	2025-01-28 21:20:42	2025-01-28 21:20:42
10	4	1	2025-01-28 21:23:06	2025-01-28 21:23:06
11	4	4	2025-01-28 21:23:06	2025-01-28 21:23:06
12	4	9	2025-01-28 21:23:06	2025-01-28 21:23:06
13	5	1	2025-01-28 21:25:32	2025-01-28 21:25:32
14	5	4	2025-01-28 21:25:32	2025-01-28 21:25:32
15	5	9	2025-01-28 21:25:32	2025-01-28 21:25:32
16	6	1	2025-01-28 21:28:28	2025-01-28 21:28:28
17	6	4	2025-01-28 21:28:28	2025-01-28 21:28:28
18	6	9	2025-01-28 21:28:28	2025-01-28 21:28:28
19	7	1	2025-01-28 21:31:28	2025-01-28 21:31:28
20	7	4	2025-01-28 21:31:28	2025-01-28 21:31:28
21	7	9	2025-01-28 21:31:28	2025-01-28 21:31:28
\.


--
-- TOC entry 4847 (class 0 OID 40551)
-- Dependencies: 384
-- Data for Name: asset_service_values; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.asset_service_values (asset_service_value_id, asset_service_id, asset_id, service_id, asset_zone_id, service_attribute_id, field_value, created_at, updated_at) FROM stdin;
1	1	3	2	6	1	23	2025-01-28 21:48:18	2025-01-28 21:48:18
2	1	3	2	6	2	x	2025-01-28 21:48:18	2025-01-28 21:48:18
3	1	3	2	6	3	2024-08-27	2025-01-28 21:48:18	2025-01-28 21:48:18
4	1	3	2	6	4	2024-08-29	2025-01-28 21:48:18	2025-01-28 21:48:18
5	2	3	3	6	1	2500	2025-01-28 21:48:29	2025-01-28 21:48:29
6	2	3	3	6	2	xxx	2025-01-28 21:48:29	2025-01-28 21:48:29
7	2	3	3	6	3	2024-08-29	2025-01-28 21:48:29	2025-01-28 21:48:29
8	2	3	3	6	4	2024-08-20	2025-01-28 21:48:29	2025-01-28 21:48:29
9	3	6	1	15	1	2	2025-01-28 22:01:59	2025-01-28 22:01:59
10	3	6	1	15	2	x	2025-01-28 22:01:59	2025-01-28 22:01:59
11	3	6	1	15	3	2024-08-28	2025-01-28 22:01:59	2025-01-28 22:01:59
12	3	6	1	15	4	2024-08-28	2025-01-28 22:01:59	2025-01-28 22:01:59
13	4	6	3	13	1	2500	2025-01-28 22:02:16	2025-01-28 22:02:16
14	4	6	3	13	2	xxx	2025-01-28 22:02:16	2025-01-28 22:02:16
15	4	6	3	13	3	2024-08-29	2025-01-28 22:02:16	2025-01-28 22:02:16
16	4	6	3	13	4	2024-08-20	2025-01-28 22:02:16	2025-01-28 22:02:16
17	5	6	3	15	1	2500	2025-01-28 22:02:16	2025-01-28 22:02:16
18	5	6	3	15	2	xxx	2025-01-28 22:02:16	2025-01-28 22:02:16
19	5	6	3	15	3	2024-08-29	2025-01-28 22:02:16	2025-01-28 22:02:16
20	5	6	3	15	4	2024-08-20	2025-01-28 22:02:16	2025-01-28 22:02:16
21	6	7	2	16	1	23	2025-01-28 22:07:05	2025-01-28 22:07:05
22	6	7	2	16	2	x	2025-01-28 22:07:05	2025-01-28 22:07:05
23	6	7	2	16	3	2024-08-27	2025-01-28 22:07:05	2025-01-28 22:07:05
24	6	7	2	16	4	2024-08-29	2025-01-28 22:07:05	2025-01-28 22:07:05
25	7	7	3	16	1	2500	2025-01-28 22:07:13	2025-01-28 22:07:13
26	7	7	3	16	2	xxx	2025-01-28 22:07:13	2025-01-28 22:07:13
27	7	7	3	16	3	2024-08-29	2025-01-28 22:07:13	2025-01-28 22:07:13
28	7	7	3	16	4	2024-08-20	2025-01-28 22:07:13	2025-01-28 22:07:13
29	8	8	1	20	1	2	2025-01-28 22:55:41	2025-01-28 22:55:41
30	8	8	1	20	2	x	2025-01-28 22:55:41	2025-01-28 22:55:41
31	8	8	1	20	3	2024-08-28	2025-01-28 22:55:41	2025-01-28 22:55:41
32	8	8	1	20	4	2024-08-28	2025-01-28 22:55:41	2025-01-28 22:55:41
33	9	8	2	19	1	23	2025-01-28 22:55:50	2025-01-28 22:55:50
34	9	8	2	19	2	x	2025-01-28 22:55:50	2025-01-28 22:55:50
35	9	8	2	19	3	2024-08-27	2025-01-28 22:55:50	2025-01-28 22:55:50
36	9	8	2	19	4	2024-08-29	2025-01-28 22:55:50	2025-01-28 22:55:50
37	10	8	3	19	1	2500	2025-01-28 22:55:58	2025-01-28 22:55:58
38	10	8	3	19	2	xxx	2025-01-28 22:55:58	2025-01-28 22:55:58
39	10	8	3	19	3	2024-08-29	2025-01-28 22:55:58	2025-01-28 22:55:58
40	10	8	3	19	4	2024-08-20	2025-01-28 22:55:58	2025-01-28 22:55:58
\.


--
-- TOC entry 4767 (class 0 OID 39693)
-- Dependencies: 304
-- Data for Name: asset_services; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.asset_services (asset_service_id, service_id, asset_id, plant_id, created_at, updated_at, deleted_at, area_id, asset_zone_id, service_type_id) FROM stdin;
1	2	3	17	2025-01-28 21:48:18	2025-01-28 21:48:18	\N	5	6	7
2	3	3	17	2025-01-28 21:48:29	2025-01-28 21:48:29	\N	5	6	7
3	1	6	17	2025-01-28 22:01:59	2025-01-28 22:01:59	\N	5	15	7
4	3	6	17	2025-01-28 22:02:16	2025-01-28 22:02:16	\N	5	13	7
5	3	6	17	2025-01-28 22:02:16	2025-01-28 22:02:16	\N	5	15	7
6	2	7	17	2025-01-28 22:07:05	2025-01-28 22:07:05	\N	5	16	7
7	3	7	17	2025-01-28 22:07:13	2025-01-28 22:07:13	\N	5	16	7
8	1	8	17	2025-01-28 22:55:41	2025-01-28 22:55:41	\N	5	20	7
9	2	8	17	2025-01-28 22:55:50	2025-01-28 22:55:50	\N	5	19	7
10	3	8	17	2025-01-28 22:55:58	2025-01-28 22:55:58	\N	5	19	7
\.


--
-- TOC entry 4845 (class 0 OID 40519)
-- Dependencies: 382
-- Data for Name: asset_spare_values; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.asset_spare_values (asset_spare_value_id, asset_spare_id, asset_id, spare_id, asset_zone_id, spare_attribute_id, field_value, created_at, updated_at) FROM stdin;
1	1	2	4	4	1		2025-01-28 21:36:46	2025-01-28 21:36:46
3	1	2	4	4	3	xyz	2025-01-28 21:36:46	2025-01-28 21:36:46
4	1	2	4	4	4	Pieces	2025-01-28 21:36:46	2025-01-28 21:36:46
5	2	2	13	4	1		2025-01-28 21:37:57	2025-01-28 21:37:57
7	2	2	13	4	3	xyz	2025-01-28 21:37:57	2025-01-28 21:37:57
8	2	2	13	4	4	Pieces	2025-01-28 21:37:57	2025-01-28 21:37:57
9	3	2	23	5	1		2025-01-28 21:38:41	2025-01-28 21:38:41
10	3	2	23	5	2		2025-01-28 21:38:41	2025-01-28 21:38:41
11	3	2	23	5	3	ACC	2025-01-28 21:38:41	2025-01-28 21:38:41
12	3	2	23	5	4	Kg	2025-01-28 21:38:41	2025-01-28 21:38:41
2	1	2	4	4	2	#f04c8e	2025-01-28 21:36:46	2025-01-28 21:39:34
6	2	2	13	4	2	#272098	2025-01-28 21:37:57	2025-01-28 21:39:58
13	4	3	25	6	1		2025-01-28 21:45:13	2025-01-28 21:45:13
14	4	3	25	6	2	#ce0d0d	2025-01-28 21:45:13	2025-01-28 21:45:13
15	4	3	25	6	3	DALMIA	2025-01-28 21:45:13	2025-01-28 21:45:13
16	4	3	25	6	4	Kg	2025-01-28 21:45:13	2025-01-28 21:45:13
17	5	3	23	8	1		2025-01-28 21:45:38	2025-01-28 21:45:38
18	5	3	23	8	2		2025-01-28 21:45:38	2025-01-28 21:45:38
19	5	3	23	8	3	ACC	2025-01-28 21:45:38	2025-01-28 21:45:38
20	5	3	23	8	4	Kg	2025-01-28 21:45:38	2025-01-28 21:45:38
21	6	3	24	7	1		2025-01-28 21:46:20	2025-01-28 21:46:20
22	6	3	24	7	2	#2e1b75	2025-01-28 21:46:20	2025-01-28 21:46:20
23	6	3	24	7	3	DALMIA	2025-01-28 21:46:20	2025-01-28 21:46:20
24	6	3	24	7	4	Kg	2025-01-28 21:46:20	2025-01-28 21:46:20
25	7	5	4	11	1		2025-01-28 21:54:22	2025-01-28 21:54:22
26	7	5	4	11	2	#e62828	2025-01-28 21:54:22	2025-01-28 21:54:22
27	7	5	4	11	3	xyz	2025-01-28 21:54:22	2025-01-28 21:54:22
28	7	5	4	11	4	Pieces	2025-01-28 21:54:22	2025-01-28 21:54:22
29	8	5	18	11	1		2025-01-28 21:55:13	2025-01-28 21:55:13
30	8	5	18	11	2	#c639a7	2025-01-28 21:55:13	2025-01-28 21:55:13
31	8	5	18	11	3	xyz	2025-01-28 21:55:13	2025-01-28 21:55:13
32	8	5	18	11	4	Pieces	2025-01-28 21:55:13	2025-01-28 21:55:13
33	9	5	23	12	1		2025-01-28 21:55:42	2025-01-28 21:55:42
34	9	5	23	12	2		2025-01-28 21:55:42	2025-01-28 21:55:42
35	9	5	23	12	3	ACC	2025-01-28 21:55:42	2025-01-28 21:55:42
36	9	5	23	12	4	Kg	2025-01-28 21:55:42	2025-01-28 21:55:42
37	10	5	24	12	1		2025-01-28 21:56:17	2025-01-28 21:56:17
38	10	5	24	12	2	#184abf	2025-01-28 21:56:17	2025-01-28 21:56:17
39	10	5	24	12	3	DALMIA	2025-01-28 21:56:17	2025-01-28 21:56:17
40	10	5	24	12	4	Kg	2025-01-28 21:56:17	2025-01-28 21:56:17
41	11	6	14	13	1		2025-01-28 21:58:28	2025-01-28 21:58:28
42	11	6	14	13	2	#e32b2b	2025-01-28 21:58:28	2025-01-28 21:58:28
43	11	6	14	13	3	xyz	2025-01-28 21:58:28	2025-01-28 21:58:28
44	11	6	14	13	4	Pieces	2025-01-28 21:58:28	2025-01-28 21:58:28
49	13	6	23	15	1		2025-01-28 21:59:19	2025-01-28 21:59:19
50	13	6	23	15	2		2025-01-28 21:59:19	2025-01-28 21:59:19
51	13	6	23	15	3	ACC	2025-01-28 21:59:19	2025-01-28 21:59:19
52	13	6	23	15	4	Kg	2025-01-28 21:59:19	2025-01-28 21:59:19
53	14	6	24	14	1		2025-01-28 22:00:03	2025-01-28 22:00:03
54	14	6	24	14	2	#cd2b8f	2025-01-28 22:00:03	2025-01-28 22:00:03
55	14	6	24	14	3	DALMIA	2025-01-28 22:00:03	2025-01-28 22:00:03
56	14	6	24	14	4	Kg	2025-01-28 22:00:03	2025-01-28 22:00:03
57	15	6	25	15	1		2025-01-28 22:00:38	2025-01-28 22:00:38
58	15	6	25	15	2	#481fdb	2025-01-28 22:00:38	2025-01-28 22:00:38
59	15	6	25	15	3	DALMIA	2025-01-28 22:00:38	2025-01-28 22:00:38
60	15	6	25	15	4	Kg	2025-01-28 22:00:38	2025-01-28 22:00:38
61	16	7	14	16	1		2025-01-28 22:04:22	2025-01-28 22:04:22
62	16	7	14	16	2	#f91010	2025-01-28 22:04:22	2025-01-28 22:04:22
63	16	7	14	16	3	xyz	2025-01-28 22:04:22	2025-01-28 22:04:22
64	16	7	14	16	4	Pieces	2025-01-28 22:04:22	2025-01-28 22:04:22
65	17	7	19	18	1		2025-01-28 22:05:04	2025-01-28 22:05:04
66	17	7	19	18	2	#0b18d0	2025-01-28 22:05:04	2025-01-28 22:05:04
67	17	7	19	18	3	xyz	2025-01-28 22:05:04	2025-01-28 22:05:04
68	17	7	19	18	4	Pieces	2025-01-28 22:05:04	2025-01-28 22:05:04
69	18	7	23	18	1		2025-01-28 22:05:20	2025-01-28 22:05:20
70	18	7	23	18	2		2025-01-28 22:05:20	2025-01-28 22:05:20
71	18	7	23	18	3	ACC	2025-01-28 22:05:20	2025-01-28 22:05:20
72	18	7	23	18	4	Kg	2025-01-28 22:05:20	2025-01-28 22:05:20
73	19	7	24	17	1		2025-01-28 22:06:02	2025-01-28 22:06:02
74	19	7	24	17	2	#e655b9	2025-01-28 22:06:02	2025-01-28 22:06:02
75	19	7	24	17	3	DALMIA	2025-01-28 22:06:02	2025-01-28 22:06:02
76	19	7	24	17	4	Kg	2025-01-28 22:06:02	2025-01-28 22:06:02
77	20	8	6	20	1		2025-01-28 22:09:27	2025-01-28 22:09:27
78	20	8	6	20	2		2025-01-28 22:09:27	2025-01-28 22:09:27
79	20	8	6	20	3	xyz	2025-01-28 22:09:27	2025-01-28 22:09:27
80	20	8	6	20	4	Pieces	2025-01-28 22:09:27	2025-01-28 22:09:27
81	21	8	25	19	1		2025-01-28 22:52:53	2025-01-28 22:52:53
82	21	8	25	19	2	#179b92	2025-01-28 22:52:53	2025-01-28 22:52:53
83	21	8	25	19	3	DALMIA	2025-01-28 22:52:53	2025-01-28 22:52:53
84	21	8	25	19	4	Kg	2025-01-28 22:52:53	2025-01-28 22:52:53
85	22	8	26	19	1		2025-01-28 22:53:20	2025-01-28 22:53:20
86	22	8	26	19	2	#96ad25	2025-01-28 22:53:20	2025-01-28 22:53:20
87	22	8	26	19	3	xyz	2025-01-28 22:53:20	2025-01-28 22:53:20
88	22	8	26	19	4	Pieces	2025-01-28 22:53:20	2025-01-28 22:53:20
\.


--
-- TOC entry 4735 (class 0 OID 39389)
-- Dependencies: 272
-- Data for Name: asset_spares; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.asset_spares (asset_spare_id, spare_id, asset_id, plant_id, created_at, updated_at, deleted_at, area_id, asset_zone_id, spare_type_id, quantity) FROM stdin;
1	4	2	17	2025-01-28 21:36:46	2025-01-28 21:36:46	\N	5	4	18	500
2	13	2	17	2025-01-28 21:37:57	2025-01-28 21:37:57	\N	5	4	18	250
3	23	2	17	2025-01-28 21:38:41	2025-01-28 21:38:41	\N	5	5	17	200
4	25	3	17	2025-01-28 21:45:13	2025-01-28 21:45:13	\N	5	6	18	2000
5	23	3	17	2025-01-28 21:45:38	2025-01-28 21:45:38	\N	5	8	17	500
6	24	3	17	2025-01-28 21:46:20	2025-01-28 21:46:20	\N	5	7	18	1500
7	4	5	17	2025-01-28 21:54:22	2025-01-28 21:54:22	\N	5	11	18	3500
8	18	5	17	2025-01-28 21:55:13	2025-01-28 21:55:13	\N	5	11	18	1500
9	23	5	17	2025-01-28 21:55:42	2025-01-28 21:55:42	\N	5	12	17	250
10	24	5	17	2025-01-28 21:56:17	2025-01-28 21:56:17	\N	5	12	18	550
11	14	6	17	2025-01-28 21:58:28	2025-01-28 21:58:28	\N	5	13	18	2500
13	23	6	17	2025-01-28 21:59:19	2025-01-28 21:59:19	\N	5	15	17	500
14	24	6	17	2025-01-28 22:00:03	2025-01-28 22:00:03	\N	5	14	18	2200
15	25	6	17	2025-01-28 22:00:38	2025-01-28 22:00:38	\N	5	15	18	1500
16	14	7	17	2025-01-28 22:04:22	2025-01-28 22:04:22	\N	5	16	18	1500
17	19	7	17	2025-01-28 22:05:04	2025-01-28 22:05:04	\N	5	18	18	1500
18	23	7	17	2025-01-28 22:05:20	2025-01-28 22:05:20	\N	5	18	17	950
19	24	7	17	2025-01-28 22:06:02	2025-01-28 22:06:02	\N	5	17	18	2200
20	6	8	17	2025-01-28 22:09:27	2025-01-28 22:09:27	\N	5	20	18	100
21	25	8	17	2025-01-28 22:52:53	2025-01-28 22:52:53	\N	5	19	18	500
22	26	8	17	2025-01-28 22:53:20	2025-01-28 22:53:20	\N	5	19	18	250
\.


--
-- TOC entry 4879 (class 0 OID 41041)
-- Dependencies: 416
-- Data for Name: asset_template_accessories; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.asset_template_accessories (asset_template_accessory_id, area_id, plant_id, asset_template_id, template_zone_id, accessory_type_id, accessory_name, attachment, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 4865 (class 0 OID 40802)
-- Dependencies: 402
-- Data for Name: asset_template_checks; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.asset_template_checks (asset_template_check_id, area_id, check_id, asset_template_id, template_zone_id, plant_id, lcl, ucl, default_value, created_at, updated_at, deleted_at) FROM stdin;
\.


--
-- TOC entry 4875 (class 0 OID 40972)
-- Dependencies: 412
-- Data for Name: asset_template_datasources; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.asset_template_datasources (asset_template_datasource_id, area_id, plant_id, asset_template_id, template_zone_id, data_source_type_id, data_source_id, script, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 4867 (class 0 OID 40834)
-- Dependencies: 404
-- Data for Name: asset_template_services; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.asset_template_services (asset_template_service_id, area_id, service_id, asset_template_id, template_zone_id, plant_id, service_type_id, created_at, updated_at, deleted_at) FROM stdin;
\.


--
-- TOC entry 4861 (class 0 OID 40733)
-- Dependencies: 398
-- Data for Name: asset_template_spares; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.asset_template_spares (asset_template_spare_id, spare_id, asset_template_id, plant_id, area_id, template_zone_id, spare_type_id, quantity, created_at, updated_at, deleted_at) FROM stdin;
\.


--
-- TOC entry 4871 (class 0 OID 40903)
-- Dependencies: 408
-- Data for Name: asset_template_variables; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.asset_template_variables (asset_template_variable_id, area_id, plant_id, asset_template_id, template_zone_id, variable_type_id, variable_id, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 4853 (class 0 OID 40652)
-- Dependencies: 390
-- Data for Name: asset_templates; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.asset_templates (asset_template_id, template_code, template_name, asset_type_id, latitude, longitude, radius, plant_id, section_id, area_id, no_of_zones, functional_id, geometry_type, height, diameter, created_at, updated_at, deleted_at) FROM stdin;
1	STL_STD_TEMPLATE	Standard Steel Laddle	32	\N	\N	\N	18	13	5	3	2	V-Cylindrical	15.00	5.00	2025-01-28 21:10:52	2025-01-28 21:10:52	\N
\.


--
-- TOC entry 4711 (class 0 OID 39251)
-- Dependencies: 248
-- Data for Name: asset_type; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.asset_type (asset_type_id, asset_type_code, asset_type_name, created_at, updated_at, deleted_at) FROM stdin;
1	KILN	Rotary Kiln	2025-01-28 18:25:54	2025-01-28 18:25:54	\N
2	BILT_CSTR	Biller Caster	2025-01-28 18:26:04	2025-01-28 18:26:04	\N
3	CSP_CSTR	CSP Caster	2025-01-28 18:26:18	2025-01-28 18:26:18	\N
4	BTF	Benzol Tube Furnace	2025-01-28 18:26:28	2025-01-28 18:26:28	\N
5	CF	Clause Furnace	2025-01-28 18:26:40	2025-01-28 18:26:40	\N
6	BLR	Boiler	2025-01-28 18:26:50	2025-01-28 18:26:50	\N
7	HGG	Hot Gas Generator	2025-01-28 18:27:03	2025-01-28 18:27:03	\N
8	INDF	Indurating Furnace	2025-01-28 18:27:15	2025-01-28 18:27:15	\N
9	SF	Sinter Furnace	2025-01-28 18:27:27	2025-01-28 18:27:27	\N
10	TIL	Torpedo Italian Ladle	2025-01-28 18:27:42	2025-01-28 18:27:42	\N
11	TJL	Torpedo Jambo Ladle	2025-01-28 18:27:55	2025-01-28 18:27:55	\N
12	DC	Dust Collector	2025-01-28 18:28:08	2025-01-28 18:28:08	\N
13	PCM	Pig Casting Machine	2025-01-28 18:28:22	2025-01-28 18:28:22	\N
14	BF	Blast Furnace	2025-01-28 18:28:37	2025-01-28 18:28:37	\N
15	SLBC	Slab Caster	2025-01-28 18:29:38	2025-01-28 18:29:38	\N
16	LF	Ladle Furnace	2025-01-28 18:29:51	2025-01-28 18:29:51	\N
17	BOF	Basic Oxygen Furnace	2025-01-28 18:30:03	2025-01-28 18:30:03	\N
18	KRL	KR Ladle	2025-01-28 18:30:15	2025-01-28 18:30:15	\N
19	LCP	LCP	2025-01-28 18:30:21	2025-01-28 18:30:21	\N
20	DRIF	DRI Furnace	2025-01-28 18:30:38	2025-01-28 18:30:38	\N
21	RF	Reheating Furnace	2025-01-28 18:30:45	2025-01-28 18:30:45	\N
22	LNDR	Launder	2025-01-28 18:30:56	2025-01-28 18:30:56	\N
23	Motor	Motor	2025-01-28 18:31:03	2025-01-28 18:31:03	\N
24	RGD	RG Duct	2025-01-28 18:31:16	2025-01-28 18:31:16	\N
25	AF	Ammonia Furnace	2025-01-28 18:31:31	2025-01-28 18:31:31	\N
26	TF	Tunnel Furnace	2025-01-28 18:31:47	2025-01-28 18:31:47	\N
27	CD	Combustion Duct	2025-01-28 18:31:59	2025-01-28 18:31:59	\N
28	FM	Furnace Main	2025-01-28 18:32:13	2025-01-28 18:32:13	\N
29	HML	Hot Metal Ladle	2025-01-28 18:32:32	2025-01-28 18:32:32	\N
30	HAD	Hot Air Duct	2025-01-28 18:32:45	2025-01-28 18:32:45	\N
31	IF	Ignition Furnace	2025-01-28 18:33:11	2025-01-28 18:33:11	\N
32	STL	Steel Ladle	2025-01-28 18:33:22	2025-01-28 18:33:22	\N
33	Offtake	Offtake	2025-01-28 18:33:29	2025-01-28 18:33:29	\N
34	TRPDO	Torpedo Ladle	2025-01-28 18:33:42	2025-01-28 18:33:42	\N
35	BM	Bar Mill	2025-01-28 18:33:54	2025-01-28 18:33:54	\N
36	CH	Cast House	2025-01-28 18:34:06	2025-01-28 18:34:06	\N
37	Tundish	Tundish	2025-01-28 18:34:15	2025-01-28 18:34:15	\N
38	Wind Box	Wind Box	2025-01-28 18:34:26	2025-01-28 18:34:26	\N
39	Coke Wharf	Coke Wharf	2025-01-28 18:34:40	2025-01-28 18:34:40	\N
40	Bustle Main	Bustle Main	2025-01-28 18:34:53	2025-01-28 18:34:53	\N
41	Duct	Process Duct	2025-01-28 18:35:03	2025-01-28 18:35:03	\N
42	Reformer	Reformer	2025-01-28 18:35:12	2025-01-28 18:35:12	\N
43	CDQ	CDQ	2025-01-28 18:35:21	2025-01-28 18:35:21	\N
44	HBM	HBM	2025-01-28 18:35:28	2025-01-28 18:35:28	\N
45	Seal Gas	Seal Gas	2025-01-28 18:35:37	2025-01-28 18:35:37	\N
46	HRU	Heat Recovery Unit	2025-01-28 18:35:50	2025-01-28 18:35:50	\N
47	Battery	Battery	2025-01-28 18:35:56	2025-01-28 18:35:56	\N
48	Hot Stove	Hot Stove	2025-01-28 18:36:07	2025-01-28 18:36:07	\N
49	ConArc	ConArc	2025-01-28 18:36:18	2025-01-28 18:36:18	\N
50	FURNACE	Furnace	2025-01-28 18:36:30	2025-01-28 18:36:30	\N
\.


--
-- TOC entry 4849 (class 0 OID 40583)
-- Dependencies: 386
-- Data for Name: asset_variable_values; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.asset_variable_values (asset_variable_value_id, asset_variable_id, asset_id, variable_id, asset_zone_id, variable_attribute_id, field_value, created_at, updated_at) FROM stdin;
1	1	2	18	4	1	1650	2025-01-28 21:41:46	2025-01-28 21:41:46
2	1	2	18	4	2	1550	2025-01-28 21:41:46	2025-01-28 21:41:46
3	1	2	18	4	3	x	2025-01-28 21:41:46	2025-01-28 21:41:46
4	1	2	18	4	4	1700	2025-01-28 21:41:46	2025-01-28 21:41:46
5	1	2	18	4	5	0	2025-01-28 21:41:46	2025-01-28 21:41:46
6	1	2	18	4	6	degC	2025-01-28 21:41:46	2025-01-28 21:41:46
7	1	2	18	4	7	Temperature	2025-01-28 21:41:46	2025-01-28 21:41:46
8	2	3	13	6	1	0.75	2025-01-28 21:49:40	2025-01-28 21:49:40
9	2	3	13	6	2	0.5	2025-01-28 21:49:40	2025-01-28 21:49:40
10	2	3	13	6	3	x	2025-01-28 21:49:40	2025-01-28 21:49:40
11	2	3	13	6	4	2	2025-01-28 21:49:40	2025-01-28 21:49:40
12	2	3	13	6	5	0	2025-01-28 21:49:40	2025-01-28 21:49:40
13	2	3	13	6	6	%	2025-01-28 21:49:40	2025-01-28 21:49:40
14	2	3	13	6	7	Concentration	2025-01-28 21:49:40	2025-01-28 21:49:40
15	3	3	18	6	1	1650	2025-01-28 21:49:56	2025-01-28 21:49:56
16	3	3	18	6	2	1550	2025-01-28 21:49:56	2025-01-28 21:49:56
17	3	3	18	6	3	x	2025-01-28 21:49:56	2025-01-28 21:49:56
18	3	3	18	6	4	1700	2025-01-28 21:49:56	2025-01-28 21:49:56
19	3	3	18	6	5	0	2025-01-28 21:49:56	2025-01-28 21:49:56
20	3	3	18	6	6	degC	2025-01-28 21:49:56	2025-01-28 21:49:56
21	3	3	18	6	7	Temperature	2025-01-28 21:49:56	2025-01-28 21:49:56
22	4	6	17	13	1	120	2025-01-28 22:02:46	2025-01-28 22:02:46
23	4	6	17	13	2	80	2025-01-28 22:02:46	2025-01-28 22:02:46
24	4	6	17	13	3	x	2025-01-28 22:02:46	2025-01-28 22:02:46
25	4	6	17	13	4	150	2025-01-28 22:02:46	2025-01-28 22:02:46
26	4	6	17	13	5	0	2025-01-28 22:02:46	2025-01-28 22:02:46
27	4	6	17	13	6	Heat/Day	2025-01-28 22:02:46	2025-01-28 22:02:46
28	4	6	17	13	7	Count	2025-01-28 22:02:46	2025-01-28 22:02:46
29	5	6	18	13	1	1650	2025-01-28 22:02:58	2025-01-28 22:02:58
30	5	6	18	13	2	1550	2025-01-28 22:02:58	2025-01-28 22:02:58
31	5	6	18	13	3	x	2025-01-28 22:02:58	2025-01-28 22:02:58
32	5	6	18	13	4	1700	2025-01-28 22:02:58	2025-01-28 22:02:58
33	5	6	18	13	5	0	2025-01-28 22:02:58	2025-01-28 22:02:58
34	5	6	18	13	6	degC	2025-01-28 22:02:58	2025-01-28 22:02:58
35	5	6	18	13	7	Temperature	2025-01-28 22:02:58	2025-01-28 22:02:58
36	6	7	17	16	1	120	2025-01-28 22:07:30	2025-01-28 22:07:30
37	6	7	17	16	2	80	2025-01-28 22:07:30	2025-01-28 22:07:30
38	6	7	17	16	3	x	2025-01-28 22:07:30	2025-01-28 22:07:30
39	6	7	17	16	4	150	2025-01-28 22:07:30	2025-01-28 22:07:30
40	6	7	17	16	5	0	2025-01-28 22:07:30	2025-01-28 22:07:30
41	6	7	17	16	6	Heat/Day	2025-01-28 22:07:30	2025-01-28 22:07:30
42	6	7	17	16	7	Count	2025-01-28 22:07:30	2025-01-28 22:07:30
43	7	7	18	16	1	1650	2025-01-28 22:07:44	2025-01-28 22:07:44
44	7	7	18	16	2	1550	2025-01-28 22:07:44	2025-01-28 22:07:44
45	7	7	18	16	3	x	2025-01-28 22:07:44	2025-01-28 22:07:44
46	7	7	18	16	4	1700	2025-01-28 22:07:44	2025-01-28 22:07:44
47	7	7	18	16	5	0	2025-01-28 22:07:44	2025-01-28 22:07:44
48	7	7	18	16	6	degC	2025-01-28 22:07:44	2025-01-28 22:07:44
49	7	7	18	16	7	Temperature	2025-01-28 22:07:44	2025-01-28 22:07:44
50	8	8	11	19	1	0.5	2025-01-28 22:56:21	2025-01-28 22:56:21
51	8	8	11	19	2	0.2	2025-01-28 22:56:21	2025-01-28 22:56:21
52	8	8	11	19	3	x	2025-01-28 22:56:21	2025-01-28 22:56:21
53	8	8	11	19	4	1	2025-01-28 22:56:21	2025-01-28 22:56:21
54	8	8	11	19	5	0	2025-01-28 22:56:21	2025-01-28 22:56:21
55	8	8	11	19	6	%	2025-01-28 22:56:21	2025-01-28 22:56:21
56	8	8	11	19	7	Concentration	2025-01-28 22:56:21	2025-01-28 22:56:21
57	9	8	16	19	1	100	2025-01-28 22:56:35	2025-01-28 22:56:35
58	9	8	16	19	2	80	2025-01-28 22:56:35	2025-01-28 22:56:35
59	9	8	16	19	3	x	2025-01-28 22:56:35	2025-01-28 22:56:35
60	9	8	16	19	4	120	2025-01-28 22:56:35	2025-01-28 22:56:35
61	9	8	16	19	5	0	2025-01-28 22:56:35	2025-01-28 22:56:35
62	9	8	16	19	6	Qnty	2025-01-28 22:56:35	2025-01-28 22:56:35
63	9	8	16	19	7	Count	2025-01-28 22:56:35	2025-01-28 22:56:35
64	10	8	17	19	1	120	2025-01-28 22:56:51	2025-01-28 22:56:51
65	10	8	17	19	2	80	2025-01-28 22:56:51	2025-01-28 22:56:51
66	10	8	17	19	3	x	2025-01-28 22:56:51	2025-01-28 22:56:51
67	10	8	17	19	4	150	2025-01-28 22:56:51	2025-01-28 22:56:51
68	10	8	17	19	5	0	2025-01-28 22:56:51	2025-01-28 22:56:51
69	10	8	17	19	6	Heat/Day	2025-01-28 22:56:51	2025-01-28 22:56:51
70	10	8	17	19	7	Count	2025-01-28 22:56:51	2025-01-28 22:56:51
71	11	8	18	19	1	1650	2025-01-28 22:57:07	2025-01-28 22:57:07
72	11	8	18	19	2	1550	2025-01-28 22:57:07	2025-01-28 22:57:07
73	11	8	18	19	3	x	2025-01-28 22:57:07	2025-01-28 22:57:07
74	11	8	18	19	4	1700	2025-01-28 22:57:07	2025-01-28 22:57:07
75	11	8	18	19	5	0	2025-01-28 22:57:07	2025-01-28 22:57:07
76	11	8	18	19	6	degC	2025-01-28 22:57:07	2025-01-28 22:57:07
77	11	8	18	19	7	Temperature	2025-01-28 22:57:07	2025-01-28 22:57:07
\.


--
-- TOC entry 4827 (class 0 OID 40233)
-- Dependencies: 364
-- Data for Name: asset_variables; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.asset_variables (asset_variable_id, area_id, plant_id, asset_id, asset_zone_id, variable_type_id, variable_id, created_at, updated_at) FROM stdin;
1	5	17	2	4	6	18	2025-01-28 21:41:46	2025-01-28 21:41:46
2	5	17	3	6	6	13	2025-01-28 21:49:40	2025-01-28 21:49:40
3	5	17	3	6	6	18	2025-01-28 21:49:56	2025-01-28 21:49:56
4	5	17	6	13	3	17	2025-01-28 22:02:46	2025-01-28 22:02:46
5	5	17	6	13	6	18	2025-01-28 22:02:58	2025-01-28 22:02:58
6	5	17	7	16	3	17	2025-01-28 22:07:30	2025-01-28 22:07:30
7	5	17	7	16	6	18	2025-01-28 22:07:44	2025-01-28 22:07:44
8	5	17	8	19	6	11	2025-01-28 22:56:21	2025-01-28 22:56:21
9	5	17	8	19	3	16	2025-01-28 22:56:35	2025-01-28 22:56:35
10	5	17	8	19	3	17	2025-01-28 22:56:51	2025-01-28 22:56:51
11	5	17	8	19	6	18	2025-01-28 22:57:07	2025-01-28 22:57:07
\.


--
-- TOC entry 4825 (class 0 OID 40180)
-- Dependencies: 362
-- Data for Name: asset_zones; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.asset_zones (asset_zone_id, asset_id, zone_name, created_at, updated_at, deleted_at, height, diameter) FROM stdin;
1	1	Overall	2025-01-28 21:13:37	2025-01-28 21:13:37	\N	10.00	5.00
2	1	Z2	2025-01-28 21:13:37	2025-01-28 21:13:37	\N	40.00	5.00
3	1	Z3	2025-01-28 21:13:37	2025-01-28 21:13:37	\N	10.00	5.00
4	2	Overall	2025-01-28 21:17:10	2025-01-28 21:17:10	\N	2.00	5.00
5	2	EBT	2025-01-28 21:17:10	2025-01-28 21:17:10	\N	0.50	5.00
6	3	Overall	2025-01-28 21:20:42	2025-01-28 21:20:42	\N	2.50	2.50
7	3	MiddleZone	2025-01-28 21:20:42	2025-01-28 21:20:42	\N	1.50	2.50
8	3	BottomZone	2025-01-28 21:20:42	2025-01-28 21:20:42	\N	1.00	2.50
9	4	Overall	2025-01-28 21:23:06	2025-01-28 21:23:06	\N	2.00	5.00
10	4	ConicalZone	2025-01-28 21:23:06	2025-01-28 21:23:06	\N	0.50	5.00
11	5	Overall	2025-01-28 21:25:32	2025-01-28 21:25:32	\N	3.00	2.50
12	5	ConicalZone	2025-01-28 21:25:32	2025-01-28 21:25:32	\N	2.00	2.50
13	6	Overall	2025-01-28 21:28:28	2025-01-28 21:28:28	\N	1.00	2.50
14	6	MiddleZone	2025-01-28 21:28:28	2025-01-28 21:28:28	\N	2.50	2.50
15	6	BottomZone	2025-01-28 21:28:28	2025-01-28 21:28:28	\N	1.50	2.50
16	7	Overall	2025-01-28 21:31:28	2025-01-28 21:31:28	\N	1.00	2.50
17	7	MiddleZone	2025-01-28 21:31:28	2025-01-28 21:31:28	\N	2.50	2.50
18	7	BottomZone	2025-01-28 21:31:28	2025-01-28 21:31:28	\N	1.50	2.50
19	8	Overall	2025-01-28 21:34:05	2025-01-28 21:34:05	\N	2.00	3.00
20	8	Bottom Ring	2025-01-28 21:34:05	2025-01-28 21:34:05	\N	4.00	3.00
\.


--
-- TOC entry 4733 (class 0 OID 39372)
-- Dependencies: 270
-- Data for Name: assets; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.assets (asset_id, plant_id, asset_code, asset_name, asset_type_id, created_at, updated_at, deleted_at, latitude, longitude, section_id, radius, area_id, no_of_zones, functional_id, geometry_type, height, diameter, asset_template_id) FROM stdin;
1	11	BLCW-KLN1	Kiln 1	1	2025-01-28 21:13:37	2025-01-28 21:13:37	\N	\N	\N	13	\N	6	3	2	H-Cylindrical	60.00	5.00	\N
2	17	CONARC#5	CONARC#5	49	2025-01-28 21:17:10	2025-01-28 21:17:10	\N	\N	\N	6	\N	5	2	2	V-Cylindrical	2.50	5.00	\N
3	17	STL#4	Steel Ladle STL#4	32	2025-01-28 21:20:42	2025-01-28 21:20:42	\N	\N	\N	16	\N	5	3	2	V-Cylindrical	5.00	2.50	\N
4	17	CONARC#4	CONARC#4	49	2025-01-28 21:23:06	2025-01-28 21:23:06	\N	\N	\N	7	\N	5	2	\N	V-Cylindrical	2.50	5.00	\N
5	17	CONARC#1	CONARC#1	49	2025-01-28 21:25:32	2025-01-28 21:25:32	\N	\N	\N	7	\N	5	2	\N	H-Cylindrical	5.00	2.50	\N
6	17	STL#3	Steel Ladle STL#3	32	2025-01-28 21:28:28	2025-01-28 21:28:28	\N	\N	\N	6	\N	5	3	2	H-Cylindrical	5.00	2.50	\N
7	17	STL#1	Steel Ladle STL#1	32	2025-01-28 21:31:28	2025-01-28 21:31:28	\N	\N	\N	6	\N	5	3	2	H-Cylindrical	5.00	2.50	\N
8	17	SMS1_LDL_01	Steel Ladle 01	32	2025-01-28 21:34:05	2025-01-28 21:34:05	\N	\N	\N	\N	\N	5	2	2	V-Cylindrical	6.00	3.00	\N
\.


--
-- TOC entry 4797 (class 0 OID 39926)
-- Dependencies: 334
-- Data for Name: break_down_attribute_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.break_down_attribute_types (break_down_attribute_type_id, break_down_attribute_id, break_down_type_id, created_at, updated_at, deleted_at) FROM stdin;
1	1	5	2025-01-28 19:26:16	2025-01-28 19:26:16	\N
2	1	4	2025-01-28 19:26:16	2025-01-28 19:26:16	\N
3	1	3	2025-01-28 19:26:16	2025-01-28 19:26:16	\N
4	1	2	2025-01-28 19:26:16	2025-01-28 19:26:16	\N
5	2	2	2025-01-28 19:26:59	2025-01-28 19:26:59	\N
6	2	3	2025-01-28 19:26:59	2025-01-28 19:26:59	\N
7	2	4	2025-01-28 19:26:59	2025-01-28 19:26:59	\N
8	2	5	2025-01-28 19:26:59	2025-01-28 19:26:59	\N
9	3	2	2025-01-28 19:27:29	2025-01-28 19:27:29	\N
10	3	3	2025-01-28 19:27:29	2025-01-28 19:27:29	\N
11	3	4	2025-01-28 19:27:29	2025-01-28 19:27:29	\N
12	3	5	2025-01-28 19:27:29	2025-01-28 19:27:29	\N
13	4	2	2025-01-28 19:27:55	2025-01-28 19:27:55	\N
14	4	3	2025-01-28 19:27:55	2025-01-28 19:27:55	\N
15	4	4	2025-01-28 19:27:55	2025-01-28 19:27:55	\N
16	4	5	2025-01-28 19:27:55	2025-01-28 19:27:55	\N
17	5	2	2025-01-28 19:28:28	2025-01-28 19:28:28	\N
18	5	3	2025-01-28 19:28:28	2025-01-28 19:28:28	\N
19	5	4	2025-01-28 19:28:28	2025-01-28 19:28:28	\N
20	5	5	2025-01-28 19:28:28	2025-01-28 19:28:28	\N
\.


--
-- TOC entry 4815 (class 0 OID 40064)
-- Dependencies: 352
-- Data for Name: break_down_attribute_values; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.break_down_attribute_values (break_down_attribute_value_id, break_down_list_id, break_down_attribute_id, field_value, created_at, updated_at, deleted_at) FROM stdin;
\.


--
-- TOC entry 4787 (class 0 OID 39843)
-- Dependencies: 324
-- Data for Name: break_down_attributes; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.break_down_attributes (break_down_attribute_id, field_name, display_name, field_type, field_values, field_length, is_required, user_id, created_at, updated_at, deleted_at, list_parameter_id) FROM stdin;
1	brkdwn_remarks	Special Remarks	Text	\N	50	f	1	2025-01-28 19:26:16	2025-01-28 19:26:16	\N	\N
2	brkdwn_reason	Break Down Reason (sub type)	Dropdown	Refractory Failure, Drive overload, Material Jam, Bearing failure, Lubrication failure, Machine Failure, Safety issue, Environmental issue,	30	t	1	2025-01-28 19:26:59	2025-01-28 19:26:59	\N	\N
3	brkdwn_hrs	Duration of Stoppage (Hrs)	Number	\N	5	t	1	2025-01-28 19:27:29	2025-01-28 19:27:29	\N	\N
4	brkdwn_todatetime	Date/Time To	Date&Time	\N	10	f	1	2025-01-28 19:27:55	2025-01-28 19:27:55	\N	\N
5	brkdwn_fromdatetime	Date/time From	Date&Time	\N	10	t	1	2025-01-28 19:28:28	2025-01-28 19:28:28	\N	\N
\.


--
-- TOC entry 4807 (class 0 OID 40001)
-- Dependencies: 344
-- Data for Name: break_down_lists; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.break_down_lists (break_down_list_id, break_down_type_id, created_at, updated_at, deleted_at, asset_id, job_no, job_date, note) FROM stdin;
\.


--
-- TOC entry 4771 (class 0 OID 39747)
-- Dependencies: 308
-- Data for Name: break_down_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.break_down_types (break_down_type_id, break_down_type_code, break_down_type_name, created_at, updated_at, deleted_at) FROM stdin;
1	REFRACTORY	Refractory related break down	2025-01-28 18:45:48	2025-01-28 18:45:48	\N
2	OTHER	Other types of break down	2025-01-28 18:46:01	2025-01-28 18:46:01	\N
3	ELECTRICAL	Electrical device related break down	2025-01-28 18:46:13	2025-01-28 18:46:13	\N
4	PROCESS	Process related break down	2025-01-28 18:46:26	2025-01-28 18:46:26	\N
5	MECHANICAL	Mechanical related break down	2025-01-28 18:46:39	2025-01-28 18:46:39	\N
\.


--
-- TOC entry 4682 (class 0 OID 39098)
-- Dependencies: 219
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache (key, value, expiration) FROM stdin;
\.


--
-- TOC entry 4683 (class 0 OID 39105)
-- Dependencies: 220
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- TOC entry 4823 (class 0 OID 40151)
-- Dependencies: 360
-- Data for Name: campaign_results; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.campaign_results (campaign_result_id, campaign_id, asset_id, location, date, file, created_at, updated_at, deleted_at, torpedo_values) FROM stdin;
\.


--
-- TOC entry 4821 (class 0 OID 40137)
-- Dependencies: 358
-- Data for Name: campaigns; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.campaigns (campaign_id, asset_id, datasource, file, created_at, updated_at, deleted_at, job_date_time, job_no, script) FROM stdin;
\.


--
-- TOC entry 4761 (class 0 OID 39642)
-- Dependencies: 298
-- Data for Name: check_asset_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.check_asset_types (check_asset_type_id, check_id, asset_type_id, created_at, updated_at, deleted_at) FROM stdin;
1	1	29	2025-01-28 19:30:03	2025-01-28 19:30:03	\N
2	2	29	2025-01-28 19:32:02	2025-01-28 19:32:02	\N
3	2	29	2025-01-28 19:32:02	2025-01-28 19:32:02	\N
4	2	29	2025-01-28 19:32:02	2025-01-28 19:32:02	\N
5	3	29	2025-01-28 19:32:50	2025-01-28 19:32:50	\N
6	4	29	2025-01-28 19:33:40	2025-01-28 19:33:40	\N
7	5	32	2025-01-28 19:34:22	2025-01-28 19:34:22	\N
8	6	32	2025-01-28 19:35:12	2025-01-28 19:35:12	\N
9	7	32	2025-01-28 19:36:05	2025-01-28 19:36:05	\N
10	8	32	2025-01-28 19:36:46	2025-01-28 19:36:46	\N
11	9	32	2025-01-28 19:37:31	2025-01-28 19:37:31	\N
12	10	32	2025-01-28 19:38:10	2025-01-28 19:38:10	\N
13	11	22	2025-01-28 19:39:13	2025-01-28 19:39:13	\N
14	12	22	2025-01-28 19:40:04	2025-01-28 19:40:04	\N
15	13	29	2025-01-28 19:41:07	2025-01-28 19:41:07	\N
16	14	29	2025-01-28 19:41:57	2025-01-28 19:41:57	\N
17	15	49	2025-01-28 19:42:41	2025-01-28 19:42:41	\N
18	16	49	2025-01-28 19:43:20	2025-01-28 19:43:20	\N
19	17	49	2025-01-28 19:44:10	2025-01-28 19:44:10	\N
20	18	49	2025-01-28 19:45:34	2025-01-28 19:45:34	\N
21	19	49	2025-01-28 19:46:13	2025-01-28 19:46:13	\N
22	20	49	2025-01-28 19:46:56	2025-01-28 19:46:56	\N
23	21	49	2025-01-28 19:47:45	2025-01-28 19:47:45	\N
24	22	49	2025-01-28 19:48:49	2025-01-28 19:48:49	\N
25	23	32	2025-01-28 19:49:32	2025-01-28 19:49:32	\N
26	24	32	2025-01-28 19:50:09	2025-01-28 19:50:09	\N
27	25	32	2025-01-28 19:50:59	2025-01-28 19:50:59	\N
28	26	32	2025-01-28 19:51:46	2025-01-28 19:51:46	\N
29	27	32	2025-01-28 19:52:35	2025-01-28 19:52:35	\N
30	28	32	2025-01-28 19:53:13	2025-01-28 19:53:13	\N
31	29	32	2025-01-28 19:54:00	2025-01-28 19:54:00	\N
32	30	32	2025-01-28 19:54:48	2025-01-28 19:54:48	\N
33	31	32	2025-01-28 19:55:31	2025-01-28 19:55:31	\N
34	32	32	2025-01-28 19:56:25	2025-01-28 19:56:25	\N
35	33	32	2025-01-28 19:57:22	2025-01-28 19:57:22	\N
\.


--
-- TOC entry 4717 (class 0 OID 39272)
-- Dependencies: 254
-- Data for Name: checks; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.checks (check_id, field_name, field_type, default_value, is_required, lcl, ucl, field_values, "order", created_at, updated_at, deleted_at, department_id) FROM stdin;
1	HML Mouth jam Condition	Select	Ok	f	\N	\N	Ok, Not Ok	7	2025-01-28 19:30:03	2025-01-28 19:30:03	\N	9
2	HML Heating Duration/Shell Temp	Number	\N	f	100	140	\N	6	2025-01-28 19:32:02	2025-01-28 19:32:02	\N	9
3	KR Heats in HML	Number	\N	t	0	5	\N	4	2025-01-28 19:32:50	2025-01-28 19:32:50	\N	9
4	No of HML in Circulation	Number	\N	f	3	8	\N	3	2025-01-28 19:33:40	2025-01-28 19:33:40	\N	9
5	Heats per day	Number	\N	f	4	10	\N	14	2025-01-28 19:34:22	2025-01-28 19:34:22	\N	9
6	STL Holding  time	Number	\N	f	30	50	\N	13	2025-01-28 19:35:12	2025-01-28 19:35:12	\N	9
7	RH Heats in STL	Number	\N	f	20	35	\N	12	2025-01-28 19:36:05	2025-01-28 19:36:05	\N	9
8	STL Heating Duration	Number	\N	f	20	30	\N	10	2025-01-28 19:36:46	2025-01-28 19:36:46	\N	9
9	STL process time	Number	\N	f	4000	6000	\N	9	2025-01-28 19:37:31	2025-01-28 19:37:31	\N	9
10	No of STL in Circulation	Number	\N	f	7	10	\N	1	2025-01-28 19:38:10	2025-01-28 19:38:10	\N	9
11	Launder Runner inspection	Select	Ok	f	\N	\N	Ok, Damaged	2	2025-01-28 19:39:13	2025-01-28 19:39:13	\N	1
12	Launder Trunion inspection	Select	Ok	f	\N	\N	Ok, Damaged	1	2025-01-28 19:40:04	2025-01-28 19:40:04	\N	1
13	HML Spout	Select	Ok	f	\N	\N	Ok, Damaged	2	2025-01-28 19:41:07	2025-01-28 19:41:07	\N	1
14	HML Belarm inspection	Select	Ok	f	\N	\N	Ok, Damaged	1	2025-01-28 19:41:57	2025-01-28 19:41:57	\N	1
15	Liquid metal Carbon	Number	\N	f	0	6	\N	8	2025-01-28 19:42:41	2025-01-28 19:42:41	\N	5
16	Liquid metal Silicon	Number	\N	f	0	2	\N	7	2025-01-28 19:43:20	2025-01-28 19:43:20	\N	5
17	Slag Content (Conarc)	Number	\N	f	0	17	\N	6	2025-01-28 19:44:10	2025-01-28 19:44:10	\N	5
18	RTD inspection/fixation	Select	Ok	f	\N	\N	Ok, Not Ok	5	2025-01-28 19:45:34	2025-01-28 19:45:34	\N	3
19	Earthing	Select	Ok	f	\N	\N	Ok, Not Ok	4	2025-01-28 19:46:13	2025-01-28 19:46:13	\N	3
20	Conarc Roof Delta water pressure test	Select	Ok	f	\N	\N	Ok, Damaged	3	2025-01-28 19:46:56	2025-01-28 19:46:56	\N	1
21	Conarc Side wall and bottom inspection	Select	Ok	f	\N	\N	Ok, Damaged	2	2025-01-28 19:47:45	2025-01-28 19:47:45	\N	1
22	Conarc Trunnion inspection	Select	Ok	f	\N	\N	Ok, Damaged	1	2025-01-28 19:48:49	2025-01-28 19:48:49	\N	1
23	Motor Winding Temperature	Number	\N	f	30	70	\N	6	2025-01-28 19:49:32	2025-01-28 19:49:32	\N	3
24	Slag Content (Steel Ladle)	Number	\N	f	0	12	\N	4	2025-01-28 19:50:09	2025-01-28 19:50:09	\N	5
25	Liquid metal Sulphur	Number	\N	f	0.5	2.5	\N	3	2025-01-28 19:50:59	2025-01-28 19:50:59	\N	5
26	Ladle hook support inspection	Select	Ok	f	\N	\N	Ok, Damaged	1	2025-01-28 19:51:46	2025-01-28 19:51:46	\N	1
27	Shell Temperature (Zone 2 Average)	Number	\N	f	60	140	\N	8	2025-01-28 19:52:35	2025-01-28 19:52:35	\N	1
28	Shell Temperature (Zone 1 Average)	Number	\N	f	60	120	\N	7	2025-01-28 19:53:13	2025-01-28 19:53:13	\N	1
29	Empty Ladle Weight (Tare) in Tons	Number	\N	f	1	5	\N	6	2025-01-28 19:54:00	2025-01-28 19:54:00	\N	1
30	Base Refractory Status	Select	GOOD	f	\N	\N	GOOD, WEAROUT, PITS, DAMAGED	4	2025-01-28 19:54:48	2025-01-28 19:54:48	\N	1
31	Wall Refractory Status	Select	GOOD	f	\N	\N	GOOD, WEAROUT, PITS, DAMAGED	3	2025-01-28 19:55:31	2025-01-28 19:55:31	\N	1
32	NDE Bearing Temperature	Number	\N	t	40	80	\N	2	2025-01-28 19:56:25	2025-01-28 19:56:25	\N	1
33	Drive End Bearing Temperature	Number	\N	f	40	80	\N	1	2025-01-28 19:57:22	2025-01-28 19:57:22	\N	1
\.


--
-- TOC entry 4753 (class 0 OID 39581)
-- Dependencies: 290
-- Data for Name: consents; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.consents (consent_id, user_id, consent, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 4805 (class 0 OID 39984)
-- Dependencies: 342
-- Data for Name: data_source_asset_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.data_source_asset_types (data_source_asset_type_id, data_source_id, asset_type_id, created_at, updated_at, deleted_at) FROM stdin;
1	1	37	2025-01-28 21:06:22	2025-01-28 21:06:22	\N
2	1	34	2025-01-28 21:06:22	2025-01-28 21:06:22	\N
3	1	50	2025-01-28 21:06:22	2025-01-28 21:06:22	\N
4	1	49	2025-01-28 21:06:22	2025-01-28 21:06:22	\N
5	2	32	2025-01-28 21:07:23	2025-01-28 21:07:23	\N
6	2	29	2025-01-28 21:07:23	2025-01-28 21:07:23	\N
\.


--
-- TOC entry 4791 (class 0 OID 39875)
-- Dependencies: 328
-- Data for Name: data_source_attribute_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.data_source_attribute_types (data_source_attribute_type_id, data_source_attribute_id, data_source_type_id, created_at, updated_at, deleted_at) FROM stdin;
1	1	6	2025-01-28 19:14:15	2025-01-28 19:14:15	\N
2	1	5	2025-01-28 19:14:15	2025-01-28 19:14:15	\N
3	1	4	2025-01-28 19:14:15	2025-01-28 19:14:15	\N
4	1	3	2025-01-28 19:14:15	2025-01-28 19:14:15	\N
5	1	2	2025-01-28 19:14:15	2025-01-28 19:14:15	\N
6	1	1	2025-01-28 19:14:15	2025-01-28 19:14:15	\N
7	1	7	2025-01-28 19:14:15	2025-01-28 19:14:15	\N
8	2	1	2025-01-28 19:14:50	2025-01-28 19:14:50	\N
9	2	2	2025-01-28 19:14:50	2025-01-28 19:14:50	\N
10	2	3	2025-01-28 19:14:50	2025-01-28 19:14:50	\N
11	2	4	2025-01-28 19:14:50	2025-01-28 19:14:50	\N
12	2	5	2025-01-28 19:14:50	2025-01-28 19:14:50	\N
13	2	6	2025-01-28 19:14:50	2025-01-28 19:14:50	\N
14	2	7	2025-01-28 19:14:50	2025-01-28 19:14:50	\N
15	3	1	2025-01-28 19:15:18	2025-01-28 19:15:18	\N
16	3	2	2025-01-28 19:15:18	2025-01-28 19:15:18	\N
17	3	3	2025-01-28 19:15:18	2025-01-28 19:15:18	\N
18	3	4	2025-01-28 19:15:18	2025-01-28 19:15:18	\N
19	3	5	2025-01-28 19:15:18	2025-01-28 19:15:18	\N
20	3	6	2025-01-28 19:15:18	2025-01-28 19:15:18	\N
21	3	7	2025-01-28 19:15:18	2025-01-28 19:15:18	\N
22	4	1	2025-01-28 19:15:50	2025-01-28 19:15:50	\N
23	4	2	2025-01-28 19:15:50	2025-01-28 19:15:50	\N
24	4	3	2025-01-28 19:15:50	2025-01-28 19:15:50	\N
25	4	4	2025-01-28 19:15:50	2025-01-28 19:15:50	\N
26	4	5	2025-01-28 19:15:50	2025-01-28 19:15:50	\N
27	4	6	2025-01-28 19:15:50	2025-01-28 19:15:50	\N
28	4	7	2025-01-28 19:15:50	2025-01-28 19:15:50	\N
\.


--
-- TOC entry 4817 (class 0 OID 40081)
-- Dependencies: 354
-- Data for Name: data_source_attribute_values; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.data_source_attribute_values (data_source_attribute_value_id, data_source_id, data_source_attribute_id, field_value, created_at, updated_at, deleted_at) FROM stdin;
1	1	1	15	2025-01-28 21:06:22	2025-01-28 21:06:22	\N
2	1	2	xxx	2025-01-28 21:06:22	2025-01-28 21:06:22	\N
3	1	3	1.1.1.1	2025-01-28 21:06:22	2025-01-28 21:06:22	\N
4	1	4	SFTP	2025-01-28 21:06:22	2025-01-28 21:06:22	\N
5	2	1	60	2025-01-28 21:07:23	2025-01-28 21:07:23	\N
6	2	2	xxxxxx	2025-01-28 21:07:23	2025-01-28 21:07:23	\N
7	2	3	1.1.1.1	2025-01-28 21:07:23	2025-01-28 21:07:23	\N
8	2	4	SFTP	2025-01-28 21:07:23	2025-01-28 21:07:23	\N
\.


--
-- TOC entry 4781 (class 0 OID 39798)
-- Dependencies: 318
-- Data for Name: data_source_attributes; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.data_source_attributes (data_source_attribute_id, field_name, display_name, field_type, field_values, field_length, is_required, user_id, created_at, updated_at, deleted_at, list_parameter_id) FROM stdin;
1	ds_freq	Auto Fetch Frequency (minutes)	Number	\N	10	f	1	2025-01-28 19:14:15	2025-01-28 19:14:15	\N	\N
2	ds_userid	Login ID	Text	\N	15	f	1	2025-01-28 19:14:50	2025-01-28 19:14:50	\N	\N
3	ds_location	Network Location (IP address)	Text	\N	15	f	1	2025-01-28 19:15:18	2025-01-28 19:15:18	\N	\N
4	ds_protocol	Communication Protocol	Dropdown	SFTP, API, SQL, TCPIP, HTTPS	10	t	1	2025-01-28 19:15:50	2025-01-28 19:15:50	\N	\N
\.


--
-- TOC entry 4769 (class 0 OID 39740)
-- Dependencies: 306
-- Data for Name: data_source_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.data_source_types (data_source_type_id, data_source_type_code, data_source_type_name, created_at, updated_at, deleted_at) FROM stdin;
1	MANUAL	Manual entry into RMS	2025-01-28 18:44:21	2025-01-28 18:44:21	\N
2	LMS	Ladle Management System server	2025-01-28 18:44:32	2025-01-28 18:44:32	\N
3	PPMS	Process Data Server	2025-01-28 18:44:44	2025-01-28 18:44:44	\N
4	SCANNER	Scanner Server	2025-01-28 18:44:55	2025-01-28 18:44:55	\N
5	API	On call data base	2025-01-28 18:45:09	2025-01-28 18:45:09	\N
6	ODBC	ODBC Data Base	2025-01-28 18:45:21	2025-01-28 18:45:21	\N
7	SFTP	File Server	2025-01-28 18:45:33	2025-01-28 18:45:33	\N
\.


--
-- TOC entry 4803 (class 0 OID 39972)
-- Dependencies: 340
-- Data for Name: data_sources; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.data_sources (data_source_id, data_source_type_id, data_source_code, data_source_name, created_at, updated_at, deleted_at) FROM stdin;
1	7	DS_TORPEDO_SCANNER	Torpedo Scanner	2025-01-28 21:06:22	2025-01-28 21:06:22	\N
2	7	DS_LDL_SCANNER	Ladle Scanner File Server	2025-01-28 21:07:23	2025-01-28 21:07:23	\N
\.


--
-- TOC entry 4705 (class 0 OID 39228)
-- Dependencies: 242
-- Data for Name: departments; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.departments (department_id, department_code, department_name, created_at, updated_at, deleted_at) FROM stdin;
1	MECH	Mechanical	2025-01-28 18:09:23	2025-01-28 18:09:23	\N
2	INST	Instrumentation	2025-01-28 18:09:33	2025-01-28 18:09:33	\N
3	ELEC	Electrical	2025-01-28 18:09:46	2025-01-28 18:09:46	\N
4	PROCS	Process	2025-01-28 18:10:00	2025-01-28 18:10:00	\N
5	QC	Quality Control	2025-01-28 18:10:18	2025-01-28 18:10:18	\N
6	CIVL	Civil	2025-01-28 18:10:30	2025-01-28 18:10:30	\N
7	MANAGEMENT	MANAGEMENT	2025-01-28 18:10:41	2025-01-28 18:10:41	\N
8	TS	Technical Services	2025-01-28 18:10:51	2025-01-28 18:10:51	\N
9	REF	Refractory Management Dept	2025-01-28 18:11:00	2025-01-28 18:11:00	\N
\.


--
-- TOC entry 4881 (class 0 OID 41084)
-- Dependencies: 418
-- Data for Name: downloaded_reports; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.downloaded_reports (download_report_id, user_id, date_time, file_name, report_name, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 4727 (class 0 OID 39334)
-- Dependencies: 264
-- Data for Name: equipment; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.equipment (equipment_id, plant_id, equipment_type_id, equipment_code, equipment_name, created_at, updated_at, deleted_at, description) FROM stdin;
\.


--
-- TOC entry 4709 (class 0 OID 39244)
-- Dependencies: 246
-- Data for Name: equipment_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.equipment_types (equipment_type_id, equipment_type_code, equipment_type_name, created_at, updated_at, deleted_at) FROM stdin;
\.


--
-- TOC entry 4688 (class 0 OID 39130)
-- Dependencies: 225
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- TOC entry 4703 (class 0 OID 39219)
-- Dependencies: 240
-- Data for Name: frequencies; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.frequencies (frequency_id, frequency_code, frequency_name, created_at, updated_at, deleted_at) FROM stdin;
\.


--
-- TOC entry 4773 (class 0 OID 39756)
-- Dependencies: 310
-- Data for Name: functionals; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.functionals (functional_id, functional_code, functional_name, created_at, updated_at, deleted_at) FROM stdin;
1	MNGMNT	Management	2025-01-28 18:23:48	2025-01-28 18:23:48	\N
2	TECH	Technical	2025-01-28 18:23:59	2025-01-28 18:23:59	\N
3	TRNG	Training	2025-01-28 18:24:10	2025-01-28 18:24:10	\N
\.


--
-- TOC entry 4686 (class 0 OID 39122)
-- Dependencies: 223
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.job_batches (id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, cancelled_at, created_at, finished_at) FROM stdin;
\.


--
-- TOC entry 4685 (class 0 OID 39113)
-- Dependencies: 222
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- TOC entry 4819 (class 0 OID 40098)
-- Dependencies: 356
-- Data for Name: list_parameters; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.list_parameters (list_parameter_id, list_parameter_name, field_values, created_at, updated_at, deleted_at) FROM stdin;
1	myLIST	Item1,Item2,Item3	2025-01-28 18:24:38	2025-01-28 18:24:38	\N
2	Composition	Almag60,Al60,Zircon70	2025-01-28 18:24:53	2025-01-28 18:24:53	\N
3	Vendor	Vendor1,Vendor2,Vendor3,Vendor4	2025-01-28 18:25:05	2025-01-28 18:25:05	\N
4	UoM	Tons,TPH,degC,LPM,Qnty,Hr,Sec,Minutes,bar,%,Heats,Count,mBar,Pieces,Ltr,Kg,Hrs,ton,Heat/Day	2025-01-28 18:25:17	2025-01-28 21:01:55	\N
\.


--
-- TOC entry 4681 (class 0 OID 39092)
-- Dependencies: 218
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000001_create_cache_table	1
2	0001_01_01_000002_create_jobs_table	1
3	0001_01_01_000003_create_areas_table	1
4	0001_01_01_000004_create_plants_table	1
5	0001_01_01_000005_create_roles_table	1
6	0001_01_01_000006_create_users_table	1
7	2024_04_26_101249_create_otps_table	1
8	2024_04_27_050105_create_frequencies_table	1
9	2024_04_27_050106_create_departments_table	1
10	2024_04_27_050852_create_sections_table	1
11	2024_04_27_052406_create_equipment_types_table	1
12	2024_04_27_053757_create_asset_type_table	1
13	2024_04_27_055325_create_spare_types_table	1
14	2024_04_27_061728_create_service_type_table	1
15	2024_04_27_075119_create_checks_table	1
16	2024_04_27_082015_create_personal_access_tokens_table	1
17	2024_04_29_111125_create_modules_table	1
18	2024_04_29_112627_create_abilities_table	1
19	2024_04_29_113422_create_role_abilities_table	1
20	2024_04_30_055130_create_equipment_table	1
21	2024_04_30_055248_create_spares_table	1
22	2024_04_30_060636_create_services_table	1
23	2024_04_30_115326_create_assets_table	1
24	2024_05_02_071349_create_asset_spares_table	1
25	2024_05_02_075218_create_asset_checks_table	1
26	2024_05_04_053054_create_reasons_table	1
27	2024_05_04_060910_create_user_activities_table	1
28	2024_05_07_054703_alter_activity_status_to_user_activities	1
29	2024_05_07_055236_create_user_services_table	1
30	2024_05_07_055724_create_user_spares_table	1
31	2024_05_09_082230_create_user_checks_table	1
32	2024_05_09_082929_create_user_asset_checks_table	1
33	2024_05_09_083813_create_user_check_attachments_table	1
34	2024_05_13_061942_alter_plant_id_to_user_activities	1
35	2024_06_26_060917_alter_table_equipment	1
36	2024_07_12_062842_create_consents_table	1
37	2024_07_23_075021_create_asset_attributes_table	1
38	2024_07_26_065228_create_asset_attribute_types_table	1
39	2024_07_26_100437_create_asset_attribute_values_table	1
40	2024_07_27_060028_create_check_asset_types_table	1
41	2024_07_27_072956_create_spare_asset_types_table	1
42	2024_07_27_080231_create_service_asset_types_table	1
43	2024_07_27_103410_create_asset_services_table	1
44	2024_08_01_084734_add_frequency_id_to_checks	1
45	2024_08_01_113128_add_frequency_id_to_services	1
46	2024_08_02_105838_add_department_id_to_assets	1
47	2024_08_02_123209_add_radius_to_assets	1
48	2024_08_03_111136_add_latitude_to_plants	1
49	2024_08_03_122745_add_lcl_to_asset_checks	1
50	2024_08_05_134231_add_is_latest_to_user_services	1
51	2024_08_21_175354_create_data_source_types_table	1
52	2024_08_22_101543_create_break_down_types_table	1
53	2024_08_22_101655_create_functionals_table	1
54	2024_08_22_114952_create_accessory_types_table	1
55	2024_08_22_124234_create_variable_types_table	1
56	2024_08_22_133737_create_spare_attributes_table	1
57	2024_08_22_133806_create_data_source_attributes_table	1
58	2024_08_22_133847_create_variable_attributes_table	1
59	2024_08_22_133908_create_service_attributes_table	1
60	2024_08_22_133931_create_break_down_attributes_table	1
61	2024_08_22_163934_create_spare_attribute_types_table	1
62	2024_08_22_182520_create_data_source_attribute_types_table	1
63	2024_08_23_105805_create_variable_attribute_types_table	1
64	2024_08_23_120243_create_service_attribute_types_table	1
65	2024_08_23_125210_create_break_down_attribute_types_table	1
66	2024_08_23_152458_create_variables_table	1
67	2024_08_23_152856_create_variable_asset_types_table	1
68	2024_08_23_155455_create_data_sources_table	1
69	2024_08_23_155514_create_data_source_asset_types_table	1
70	2024_08_23_170601_create_break_down_lists_table	1
71	2024_08_24_113624_create_spare_attribute_values_table	1
72	2024_08_24_125524_create_service_attribute_values_table	1
73	2024_08_24_131236_create_variable_attribute_values_table	1
74	2024_08_24_152711_create_break_down_attribute_values_table	1
75	2024_08_24_161441_create_data_source_attribute_values_table	1
76	2024_08_27_125508_create_list_parameters_table	1
77	2024_08_28_171712_add_list_parameter_id_to_variable_attributes_table	1
78	2024_08_28_171940_add_list_parameter_id_to_break_down_attributes_table	1
79	2024_08_28_172045_add_list_parameter_id_to_data_source_attributes_table	1
80	2024_08_28_172211_add_list_parameter_id_to_spare_attributes_table	1
81	2024_08_28_172334_add_list_parameter_id_to_service_attributes_table	1
82	2024_08_28_172534_add_list_parameter_id_to_asset_attributes_table	1
83	2024_08_29_161156_remove_frequency_id_from_checks_table	1
84	2024_08_29_161614_remove_frequency_id_from_services_table	1
85	2024_08_30_153825_create_campaigns_table	1
86	2024_08_30_154617_create_campaign_results_table	1
87	2024_09_03_114846_add_fields_to_assets_table	1
88	2024_09_03_120426_create_asset_zones_table	1
89	2024_09_04_122151_add_fields_to_asset_checks_table	1
90	2024_09_04_123224_add_fields_to_asset_spares_table	1
91	2024_09_04_125510_add_fields_to_asset_services_table	1
92	2024_09_04_133058_create_asset_variables_table	1
93	2024_09_04_133816_create_asset_data_sources_table	1
94	2024_09_04_134008_create_asset_accessories_table	1
95	2024_09_09_112507_add_asset_zone_id_to_user_services_table	1
96	2024_09_09_164053_add_asset_zone_id_to_user_checks_table	1
97	2024_09_10_145133_create_user_variables_table	1
98	2024_09_10_145854_create_user_asset_variables_table	1
99	2024_09_12_120645_add_department_id_to_checks	1
100	2024_09_12_160703_remove_equipment_id_from_user_activities	1
101	2024_09_13_120305_remove_service_id_from_user_services	1
102	2024_09_13_121448_add_service_id_to_user_spares	1
103	2024_09_13_145452_add_department_id_id_to_user_checks	1
104	2024_09_13_173055_add_asset_id_to_break_down_lists	1
105	2024_09_18_103245_create_activity_attributes_table	1
106	2024_09_18_105109_create_activity_attribute_types_table	1
107	2024_09_19_112637_create_asset_departments_table	1
108	2024_09_19_121444_remove_department_id_to_assets	1
109	2024_09_19_150006_add_quantity_to_asset_spares	1
110	2024_09_19_151916_create_activity_attribute_values_table	1
111	2024_09_19_155757_add_script_to_asset_data_sources	1
112	2024_09_19_164718_add_job_date_time_to_campaigns	1
113	2024_09_20_102557_add_quantity_to_user_spares	1
114	2024_09_20_164841_add_asset_variable_id_to_user_variables	1
115	2024_09_21_132525_create_asset_spare_values	1
116	2024_09_21_163215_create_asset_service_values	1
117	2024_09_21_181847_create_asset_variable_values	1
118	2024_09_21_183752_create_asset_data_source_values	1
119	2024_09_24_160428_add_asset_zone_id_to_user_asset_variables_table	1
120	2024_09_24_161055_remove_asset_zone_id_to_user_variables_table	1
121	2024_09_28_115012_add_height_diameter_to_assets_table	1
122	2024_09_28_122756_add_height_diameter_to_asset_zones_table	1
123	2024_10_18_114649_add_torpedo_value_to_campaign_results_table	1
124	2024_11_07_121455_create_asset_templates_table	1
125	2024_11_07_123245_create_template_zones_table	1
126	2024_11_07_123254_create_template_departments_table	1
127	2024_11_07_124803_create_template_attribute_values_table	1
128	2024_11_09_163931_create_asset_template_spares_table	1
129	2024_11_09_164928_create_template_spare_values_table	1
130	2024_11_11_125218_create_asset_template_checks_table	1
131	2024_11_11_153138_create_asset_template_services_table	1
132	2024_11_11_153641_create_template_service_values_table	1
133	2024_11_11_170704_create_asset_template_variables_table	1
134	2024_11_11_171026_create_template_variable_values_table	1
135	2024_11_12_103313_create_asset_template_datasources_table	1
136	2024_11_12_103618_create_template_datasource_values_table	1
137	2024_11_12_122505_create_asset_template_accessories_table	1
138	2024_11_12_160914_add_asset_template_id_to_assets	1
139	2024_11_14_113017_add_default_value_to_checks_table	1
140	2024_12_03_202917_create_downloaded_reports_table	1
141	2024_12_06_145944_add_remark_status_to_user_asset_checks	1
142	2024_12_07_101055_add_department_to_users_table	1
143	2024_12_20_131731_add_remark_date_to_user_asset_checks	1
144	2024_12_20_155635_add_default_value_to_user_asset_checks	1
\.


--
-- TOC entry 4721 (class 0 OID 39294)
-- Dependencies: 258
-- Data for Name: modules; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.modules (module_id, module_name, description, created_at, updated_at) FROM stdin;
1	Roles	\N	2025-01-28 18:06:16	2025-01-28 18:06:16
2	Users	\N	2025-01-28 18:06:16	2025-01-28 18:06:16
3	Clusters	\N	2025-01-28 18:06:17	2025-01-28 18:06:17
4	Plants	\N	2025-01-28 18:06:17	2025-01-28 18:06:17
5	Sections	\N	2025-01-28 18:06:17	2025-01-28 18:06:17
6	EquipmentTypes	\N	2025-01-28 18:06:17	2025-01-28 18:06:17
7	AssetTypes	\N	2025-01-28 18:06:17	2025-01-28 18:06:17
8	SpareTypes	\N	2025-01-28 18:06:17	2025-01-28 18:06:17
9	ServiceTypes	\N	2025-01-28 18:06:17	2025-01-28 18:06:17
10	Voltages	\N	2025-01-28 18:06:17	2025-01-28 18:06:17
11	WattRatings	\N	2025-01-28 18:06:17	2025-01-28 18:06:17
12	Frames	\N	2025-01-28 18:06:17	2025-01-28 18:06:17
13	Mountings	\N	2025-01-28 18:06:17	2025-01-28 18:06:17
14	Makes	\N	2025-01-28 18:06:17	2025-01-28 18:06:17
15	Speeds	\N	2025-01-28 18:06:17	2025-01-28 18:06:17
16	Checks	\N	2025-01-28 18:06:17	2025-01-28 18:06:17
17	Equipment	\N	2025-01-28 18:06:17	2025-01-28 18:06:17
18	Spares	\N	2025-01-28 18:06:17	2025-01-28 18:06:17
19	Services	\N	2025-01-28 18:06:17	2025-01-28 18:06:17
20	Assets	\N	2025-01-28 18:06:17	2025-01-28 18:06:17
21	AssetParameters	\N	2025-01-28 18:06:17	2025-01-28 18:06:17
22	AssetViews	\N	2025-01-28 18:06:17	2025-01-28 18:06:17
23	AssetSpares	\N	2025-01-28 18:06:17	2025-01-28 18:06:17
24	AssetChecks	\N	2025-01-28 18:06:17	2025-01-28 18:06:17
25	Reasons	\N	2025-01-28 18:06:17	2025-01-28 18:06:17
26	UserActivities	\N	2025-01-28 18:06:17	2025-01-28 18:06:17
27	UserServices	\N	2025-01-28 18:06:17	2025-01-28 18:06:17
28	UserChecks	\N	2025-01-28 18:06:17	2025-01-28 18:06:17
29	Permissions	\N	2025-01-28 18:06:17	2025-01-28 18:06:17
\.


--
-- TOC entry 4701 (class 0 OID 39207)
-- Dependencies: 238
-- Data for Name: otps; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.otps (otp_id, user_id, otp, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 4698 (class 0 OID 39189)
-- Dependencies: 235
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.password_reset_tokens (id, email, otp, token, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 4719 (class 0 OID 39282)
-- Dependencies: 256
-- Data for Name: personal_access_tokens; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.personal_access_tokens (id, tokenable_type, tokenable_id, name, token, abilities, last_used_at, expires_at, created_at, updated_at) FROM stdin;
2	App\\Models\\User	1	token	ccd05748fb750e2d4269109e02d4ddb6672c95d1371d0f347ad7b1cba131633e	["*"]	2025-01-28 22:58:33	\N	2025-01-28 18:06:22	2025-01-28 22:58:33
\.


--
-- TOC entry 4692 (class 0 OID 39149)
-- Dependencies: 229
-- Data for Name: plants; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.plants (plant_id, plant_code, plant_name, area_id, created_at, updated_at, deleted_at, latitude, longitude, radius) FROM stdin;
2	COP#2	COP#2	2	2025-01-28 18:14:36	2025-01-28 18:14:36	\N	\N	\N	\N
3	COP#1	COP#1	2	2025-01-28 18:14:48	2025-01-28 18:14:48	\N	\N	\N	\N
4	Pellet Plant 2	Pellet Plant 2	3	2025-01-28 18:14:59	2025-01-28 18:14:59	\N	\N	\N	\N
5	Pellet Plant 1	Pellet Plant 1	3	2025-01-28 18:15:08	2025-01-28 18:15:08	\N	\N	\N	\N
6	Sinter Plant 2	Sinter Plant 2	3	2025-01-28 18:15:17	2025-01-28 18:15:17	\N	\N	\N	\N
7	Sinter Plant 1	Sinter Plant 1	3	2025-01-28 18:15:28	2025-01-28 18:15:28	\N	\N	\N	\N
8	Blast Furnace 2	Blast Furnace 2	4	2025-01-28 18:15:39	2025-01-28 18:15:39	\N	\N	\N	\N
9	Blast Furnace 1	Blast Furnace 1	4	2025-01-28 18:15:51	2025-01-28 18:15:51	\N	\N	\N	\N
10	HSM 2	HSM 2	6	2025-01-28 18:16:05	2025-01-28 18:16:05	\N	\N	\N	\N
11	SMS 2	SMS 2	6	2025-01-28 18:16:19	2025-01-28 18:16:19	\N	\N	\N	\N
12	LCP	LCP	5	2025-01-28 18:16:30	2025-01-28 18:16:30	\N	\N	\N	\N
13	Bar Mill	Bar Mill	5	2025-01-28 18:16:44	2025-01-28 18:16:44	\N	\N	\N	\N
14	Billet Caster	Billet Caster	5	2025-01-28 18:16:54	2025-01-28 18:16:54	\N	\N	\N	\N
15	CSP Mill	CSP Mill	5	2025-01-28 18:17:04	2025-01-28 18:17:04	\N	\N	\N	\N
16	CSP Caster	CSP Caster	5	2025-01-28 18:17:14	2025-01-28 18:17:14	\N	\N	\N	\N
17	SMS 1	SMS 1	5	2025-01-28 18:17:22	2025-01-28 18:17:22	\N	\N	\N	\N
18	SMS-1	Steel Melting Shop	5	2025-01-28 18:17:36	2025-01-28 18:17:36	\N	\N	\N	\N
19	LP	Lime Plant	5	2025-01-28 18:17:55	2025-01-28 18:17:55	\N	\N	\N	\N
20	Sinter	Sinter	5	2025-01-28 18:18:04	2025-01-28 18:18:04	\N	\N	\N	\N
21	SIP	SIP	5	2025-01-28 18:18:23	2025-01-28 18:18:23	\N	\N	\N	\N
22	PP	PP	5	2025-01-28 18:18:28	2025-01-28 18:18:28	\N	\N	\N	\N
23	CO	Coke Oven	5	2025-01-28 18:18:50	2025-01-28 18:18:50	\N	\N	\N	\N
24	BF-1	BF-1	5	2025-01-28 18:19:20	2025-01-28 18:19:20	\N	\N	\N	\N
1	JSW Steal Dolvi	JSW Steal Dolvi	1	\N	2025-01-28 18:19:52	2025-01-28 18:19:52	\N	\N	\N
25	1234	SMS-1	5	2025-01-28 18:19:41	2025-01-28 18:20:02	2025-01-28 18:20:02	\N	\N	\N
\.


--
-- TOC entry 4739 (class 0 OID 39433)
-- Dependencies: 276
-- Data for Name: reasons; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.reasons (reason_id, reason_code, reason_name, created_at, updated_at, deleted_at) FROM stdin;
1	REF_Patching	Refractory Patching - Part Lining	2025-01-28 18:43:27	2025-01-28 18:43:27	\N
2	REF_NewCampaign	Refractory Relining - New Campaign	2025-01-28 18:43:39	2025-01-28 18:43:39	\N
3	Scraped	Scraped	2025-01-28 18:43:51	2025-01-28 18:43:51	\N
4	Removed	Removed	2025-01-28 18:44:00	2025-01-28 18:44:00	\N
5	Added	Added	2025-01-28 18:44:06	2025-01-28 18:44:06	\N
\.


--
-- TOC entry 4725 (class 0 OID 39317)
-- Dependencies: 262
-- Data for Name: role_abilities; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.role_abilities (role_ability_id, role_id, ability_id, created_at, updated_at) FROM stdin;
1	1	1	2025-01-28 18:06:16	2025-01-28 18:06:16
2	1	2	2025-01-28 18:06:16	2025-01-28 18:06:16
3	1	3	2025-01-28 18:06:16	2025-01-28 18:06:16
4	1	4	2025-01-28 18:06:16	2025-01-28 18:06:16
5	1	5	2025-01-28 18:06:16	2025-01-28 18:06:16
6	1	6	2025-01-28 18:06:16	2025-01-28 18:06:16
7	1	7	2025-01-28 18:06:16	2025-01-28 18:06:16
8	1	8	2025-01-28 18:06:16	2025-01-28 18:06:16
9	1	9	2025-01-28 18:06:17	2025-01-28 18:06:17
10	1	10	2025-01-28 18:06:17	2025-01-28 18:06:17
11	1	11	2025-01-28 18:06:17	2025-01-28 18:06:17
12	1	12	2025-01-28 18:06:17	2025-01-28 18:06:17
13	1	13	2025-01-28 18:06:17	2025-01-28 18:06:17
14	1	14	2025-01-28 18:06:17	2025-01-28 18:06:17
15	1	15	2025-01-28 18:06:17	2025-01-28 18:06:17
16	1	16	2025-01-28 18:06:17	2025-01-28 18:06:17
17	1	17	2025-01-28 18:06:17	2025-01-28 18:06:17
18	1	18	2025-01-28 18:06:17	2025-01-28 18:06:17
19	1	19	2025-01-28 18:06:17	2025-01-28 18:06:17
20	1	20	2025-01-28 18:06:17	2025-01-28 18:06:17
21	1	21	2025-01-28 18:06:17	2025-01-28 18:06:17
22	1	22	2025-01-28 18:06:17	2025-01-28 18:06:17
23	1	23	2025-01-28 18:06:17	2025-01-28 18:06:17
24	1	24	2025-01-28 18:06:17	2025-01-28 18:06:17
25	1	25	2025-01-28 18:06:17	2025-01-28 18:06:17
26	1	26	2025-01-28 18:06:17	2025-01-28 18:06:17
27	1	27	2025-01-28 18:06:17	2025-01-28 18:06:17
28	1	28	2025-01-28 18:06:17	2025-01-28 18:06:17
29	1	29	2025-01-28 18:06:17	2025-01-28 18:06:17
30	1	30	2025-01-28 18:06:17	2025-01-28 18:06:17
31	1	31	2025-01-28 18:06:17	2025-01-28 18:06:17
32	1	32	2025-01-28 18:06:17	2025-01-28 18:06:17
33	1	33	2025-01-28 18:06:17	2025-01-28 18:06:17
34	1	34	2025-01-28 18:06:17	2025-01-28 18:06:17
35	1	35	2025-01-28 18:06:17	2025-01-28 18:06:17
36	1	36	2025-01-28 18:06:17	2025-01-28 18:06:17
37	1	37	2025-01-28 18:06:17	2025-01-28 18:06:17
38	1	38	2025-01-28 18:06:17	2025-01-28 18:06:17
39	1	39	2025-01-28 18:06:17	2025-01-28 18:06:17
40	1	40	2025-01-28 18:06:17	2025-01-28 18:06:17
41	1	41	2025-01-28 18:06:17	2025-01-28 18:06:17
42	1	42	2025-01-28 18:06:17	2025-01-28 18:06:17
43	1	43	2025-01-28 18:06:17	2025-01-28 18:06:17
44	1	44	2025-01-28 18:06:17	2025-01-28 18:06:17
45	1	45	2025-01-28 18:06:17	2025-01-28 18:06:17
46	1	46	2025-01-28 18:06:17	2025-01-28 18:06:17
47	1	47	2025-01-28 18:06:17	2025-01-28 18:06:17
48	1	48	2025-01-28 18:06:17	2025-01-28 18:06:17
49	1	49	2025-01-28 18:06:17	2025-01-28 18:06:17
50	1	50	2025-01-28 18:06:17	2025-01-28 18:06:17
51	1	51	2025-01-28 18:06:17	2025-01-28 18:06:17
52	1	52	2025-01-28 18:06:17	2025-01-28 18:06:17
53	1	53	2025-01-28 18:06:17	2025-01-28 18:06:17
54	1	54	2025-01-28 18:06:17	2025-01-28 18:06:17
55	1	55	2025-01-28 18:06:17	2025-01-28 18:06:17
56	1	56	2025-01-28 18:06:17	2025-01-28 18:06:17
57	1	57	2025-01-28 18:06:17	2025-01-28 18:06:17
58	1	58	2025-01-28 18:06:17	2025-01-28 18:06:17
59	1	59	2025-01-28 18:06:17	2025-01-28 18:06:17
60	1	60	2025-01-28 18:06:17	2025-01-28 18:06:17
61	1	61	2025-01-28 18:06:17	2025-01-28 18:06:17
62	1	62	2025-01-28 18:06:17	2025-01-28 18:06:17
63	1	63	2025-01-28 18:06:17	2025-01-28 18:06:17
64	1	64	2025-01-28 18:06:17	2025-01-28 18:06:17
65	1	65	2025-01-28 18:06:17	2025-01-28 18:06:17
66	1	66	2025-01-28 18:06:17	2025-01-28 18:06:17
67	1	67	2025-01-28 18:06:17	2025-01-28 18:06:17
68	1	68	2025-01-28 18:06:17	2025-01-28 18:06:17
69	1	69	2025-01-28 18:06:17	2025-01-28 18:06:17
70	1	70	2025-01-28 18:06:17	2025-01-28 18:06:17
71	1	71	2025-01-28 18:06:17	2025-01-28 18:06:17
72	1	72	2025-01-28 18:06:17	2025-01-28 18:06:17
73	1	73	2025-01-28 18:06:17	2025-01-28 18:06:17
74	1	74	2025-01-28 18:06:17	2025-01-28 18:06:17
75	1	75	2025-01-28 18:06:17	2025-01-28 18:06:17
76	1	76	2025-01-28 18:06:17	2025-01-28 18:06:17
77	1	77	2025-01-28 18:06:17	2025-01-28 18:06:17
78	1	78	2025-01-28 18:06:17	2025-01-28 18:06:17
79	1	79	2025-01-28 18:06:17	2025-01-28 18:06:17
80	1	80	2025-01-28 18:06:17	2025-01-28 18:06:17
81	1	81	2025-01-28 18:06:17	2025-01-28 18:06:17
82	1	82	2025-01-28 18:06:17	2025-01-28 18:06:17
83	1	83	2025-01-28 18:06:17	2025-01-28 18:06:17
84	1	84	2025-01-28 18:06:17	2025-01-28 18:06:17
85	1	85	2025-01-28 18:06:17	2025-01-28 18:06:17
86	1	86	2025-01-28 18:06:17	2025-01-28 18:06:17
87	1	87	2025-01-28 18:06:17	2025-01-28 18:06:17
88	1	88	2025-01-28 18:06:17	2025-01-28 18:06:17
89	1	89	2025-01-28 18:06:17	2025-01-28 18:06:17
90	1	90	2025-01-28 18:06:17	2025-01-28 18:06:17
91	1	91	2025-01-28 18:06:17	2025-01-28 18:06:17
92	1	92	2025-01-28 18:06:17	2025-01-28 18:06:17
93	1	93	2025-01-28 18:06:17	2025-01-28 18:06:17
94	1	94	2025-01-28 18:06:17	2025-01-28 18:06:17
95	1	95	2025-01-28 18:06:17	2025-01-28 18:06:17
96	1	96	2025-01-28 18:06:17	2025-01-28 18:06:17
97	1	97	2025-01-28 18:06:17	2025-01-28 18:06:17
98	1	98	2025-01-28 18:06:17	2025-01-28 18:06:17
99	1	99	2025-01-28 18:06:17	2025-01-28 18:06:17
100	1	100	2025-01-28 18:06:17	2025-01-28 18:06:17
101	1	101	2025-01-28 18:06:17	2025-01-28 18:06:17
102	1	102	2025-01-28 18:06:17	2025-01-28 18:06:17
103	1	103	2025-01-28 18:06:17	2025-01-28 18:06:17
104	1	104	2025-01-28 18:06:17	2025-01-28 18:06:17
105	1	105	2025-01-28 18:06:17	2025-01-28 18:06:17
106	1	106	2025-01-28 18:06:17	2025-01-28 18:06:17
107	1	107	2025-01-28 18:06:17	2025-01-28 18:06:17
108	1	108	2025-01-28 18:06:17	2025-01-28 18:06:17
\.


--
-- TOC entry 4694 (class 0 OID 39161)
-- Dependencies: 231
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.roles (role_id, role, description, created_at, updated_at, deleted_at) FROM stdin;
2	Vendor	Subcontract Vendor	2025-01-28 18:06:55	2025-01-28 18:06:55	\N
3	User	RMS User	2025-01-28 18:07:14	2025-01-28 18:07:14	\N
4	Supervisor	Section Supervisor	2025-01-28 18:07:30	2025-01-28 18:07:30	\N
5	Manager	Section Manager	2025-01-28 18:07:54	2025-01-28 18:07:54	\N
1	Admin	System Admin	\N	2025-01-28 18:08:05	\N
\.


--
-- TOC entry 4707 (class 0 OID 39237)
-- Dependencies: 244
-- Data for Name: sections; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.sections (section_id, section_code, section_name, created_at, updated_at, deleted_at) FROM stdin;
1	GRNLTN	Granulation	2025-01-28 18:20:27	2025-01-28 18:20:27	\N
2	MNTNCE	Maintainance	2025-01-28 18:20:36	2025-01-28 18:20:36	\N
3	STRG	Storage	2025-01-28 18:20:46	2025-01-28 18:20:46	\N
4	RPR	Repair	2025-01-28 18:21:00	2025-01-28 18:21:00	\N
5	WRKSHP	Workshop	2025-01-28 18:21:14	2025-01-28 18:21:14	\N
6	LDNG	Loading	2025-01-28 18:21:25	2025-01-28 18:21:25	\N
7	UNLDNG	Unloading	2025-01-28 18:21:35	2025-01-28 18:21:35	\N
8	TPNG	Tapping	2025-01-28 18:21:46	2025-01-28 18:21:46	\N
9	STV	Stove	2025-01-28 18:21:59	2025-01-28 18:21:59	\N
10	SCNG	Scanning	2025-01-28 18:22:09	2025-01-28 18:22:09	\N
11	INSPN	Inspection	2025-01-28 18:22:19	2025-01-28 18:22:19	\N
12	FRNC	Furnace	2025-01-28 18:22:31	2025-01-28 18:22:31	\N
13	HTNG	Heating	2025-01-28 18:22:45	2025-01-28 18:22:45	\N
14	CSTNG	Casting	2025-01-28 18:22:57	2025-01-28 18:22:57	\N
15	MLDNG	Moulding	2025-01-28 18:23:11	2025-01-28 18:23:11	\N
16	MLTNG	Melting	2025-01-28 18:23:22	2025-01-28 18:23:22	\N
\.


--
-- TOC entry 4765 (class 0 OID 39676)
-- Dependencies: 302
-- Data for Name: service_asset_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.service_asset_types (service_asset_type_id, service_id, asset_type_id, created_at, updated_at, deleted_at) FROM stdin;
1	1	32	2025-01-28 20:30:36	2025-01-28 20:30:36	\N
2	1	29	2025-01-28 20:30:36	2025-01-28 20:30:36	\N
3	2	32	2025-01-28 20:31:32	2025-01-28 20:31:32	\N
4	3	32	2025-01-28 20:32:36	2025-01-28 20:32:36	\N
\.


--
-- TOC entry 4795 (class 0 OID 39909)
-- Dependencies: 332
-- Data for Name: service_attribute_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.service_attribute_types (service_attribute_type_id, service_attribute_id, service_type_id, created_at, updated_at, deleted_at) FROM stdin;
1	1	3	2025-01-28 19:23:26	2025-01-28 19:23:26	\N
2	1	4	2025-01-28 19:23:26	2025-01-28 19:23:26	\N
3	1	5	2025-01-28 19:23:26	2025-01-28 19:23:26	\N
4	1	6	2025-01-28 19:23:26	2025-01-28 19:23:26	\N
5	1	7	2025-01-28 19:23:26	2025-01-28 19:23:26	\N
6	1	8	2025-01-28 19:23:26	2025-01-28 19:23:26	\N
7	2	3	2025-01-28 19:24:17	2025-01-28 19:24:17	\N
8	2	4	2025-01-28 19:24:17	2025-01-28 19:24:17	\N
9	2	5	2025-01-28 19:24:17	2025-01-28 19:24:17	\N
10	2	6	2025-01-28 19:24:17	2025-01-28 19:24:17	\N
11	2	7	2025-01-28 19:24:17	2025-01-28 19:24:17	\N
12	2	8	2025-01-28 19:24:17	2025-01-28 19:24:17	\N
13	3	3	2025-01-28 19:24:50	2025-01-28 19:24:50	\N
14	3	4	2025-01-28 19:24:50	2025-01-28 19:24:50	\N
15	3	5	2025-01-28 19:24:50	2025-01-28 19:24:50	\N
16	3	6	2025-01-28 19:24:50	2025-01-28 19:24:50	\N
17	3	7	2025-01-28 19:24:50	2025-01-28 19:24:50	\N
18	3	8	2025-01-28 19:24:50	2025-01-28 19:24:50	\N
19	4	3	2025-01-28 19:25:24	2025-01-28 19:25:24	\N
20	4	4	2025-01-28 19:25:24	2025-01-28 19:25:24	\N
21	4	5	2025-01-28 19:25:24	2025-01-28 19:25:24	\N
22	4	6	2025-01-28 19:25:24	2025-01-28 19:25:24	\N
23	4	7	2025-01-28 19:25:24	2025-01-28 19:25:24	\N
24	4	8	2025-01-28 19:25:24	2025-01-28 19:25:24	\N
\.


--
-- TOC entry 4811 (class 0 OID 40030)
-- Dependencies: 348
-- Data for Name: service_attribute_values; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.service_attribute_values (service_attribute_value_id, service_id, service_attribute_id, field_value, created_at, updated_at, deleted_at) FROM stdin;
1	1	1	2	2025-01-28 20:30:36	2025-01-28 20:30:36	\N
2	1	2	x	2025-01-28 20:30:36	2025-01-28 20:30:36	\N
3	1	3	2024-08-28	2025-01-28 20:30:36	2025-01-28 20:30:36	\N
4	1	4	2024-08-28	2025-01-28 20:30:36	2025-01-28 20:30:36	\N
5	2	1	23	2025-01-28 20:31:32	2025-01-28 20:31:32	\N
6	2	2	x	2025-01-28 20:31:32	2025-01-28 20:31:32	\N
7	2	3	2024-08-27	2025-01-28 20:31:32	2025-01-28 20:31:32	\N
8	2	4	2024-08-29	2025-01-28 20:31:32	2025-01-28 20:31:32	\N
9	3	1	2500	2025-01-28 20:32:36	2025-01-28 20:32:36	\N
10	3	2	xxx	2025-01-28 20:32:36	2025-01-28 20:32:36	\N
11	3	3	2024-08-29	2025-01-28 20:32:36	2025-01-28 20:32:36	\N
12	3	4	2024-08-20	2025-01-28 20:32:36	2025-01-28 20:32:36	\N
\.


--
-- TOC entry 4785 (class 0 OID 39828)
-- Dependencies: 322
-- Data for Name: service_attributes; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.service_attributes (service_attribute_id, field_name, display_name, field_type, field_values, field_length, is_required, user_id, created_at, updated_at, deleted_at, list_parameter_id) FROM stdin;
1	srv_cost	Total Cost	Number	\N	10	f	1	2025-01-28 19:23:26	2025-01-28 19:23:26	\N	\N
2	srv_supervisor	Name of the Supervisor	Text	\N	10	f	1	2025-01-28 19:24:17	2025-01-28 19:24:17	\N	\N
3	srv_nextdue	Next Due Date	Date	\N	10	t	1	2025-01-28 19:24:50	2025-01-28 19:24:50	\N	\N
4	srv_date	Date of Service	Date	\N	10	t	1	2025-01-28 19:25:24	2025-01-28 19:25:24	\N	\N
\.


--
-- TOC entry 4715 (class 0 OID 39265)
-- Dependencies: 252
-- Data for Name: service_type; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.service_type (service_type_id, service_type_code, service_type_name, created_at, updated_at, deleted_at) FROM stdin;
1	RELNG	Refractory Relining	2025-01-28 18:36:50	2025-01-28 18:36:50	\N
2	PTCHNG	Refractory Patching	2025-01-28 18:37:03	2025-01-28 18:37:03	\N
3	REF	Refractory related minor services	2025-01-28 18:37:14	2025-01-28 18:37:14	\N
4	EMS	Emergency Maintenance Services	2025-01-28 18:37:26	2025-01-28 18:37:26	\N
5	RO	Regular Overhauling	2025-01-28 18:37:39	2025-01-28 18:37:39	\N
6	SRS	Stoppage Related Services	2025-01-28 18:37:49	2025-01-28 18:37:49	\N
7	ORB	On Requirement basis	2025-01-28 18:38:01	2025-01-28 18:38:01	\N
8	RAM	Regular Annual Maintenance	2025-01-28 18:38:15	2025-01-28 18:38:15	\N
\.


--
-- TOC entry 4731 (class 0 OID 39365)
-- Dependencies: 268
-- Data for Name: services; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.services (service_id, service_type_id, service_code, service_name, created_at, updated_at, deleted_at) FROM stdin;
1	7	LDL_PLATE_CHNG	Plate Change	2025-01-28 20:30:36	2025-01-28 20:30:36	\N
2	7	LDL_LIPJAM_REMOVAL	Removal of Lip Jam	2025-01-28 20:31:32	2025-01-28 20:31:32	\N
3	7	LDL_PLUG_CHANGE	Plug Change	2025-01-28 20:32:36	2025-01-28 20:32:36	\N
\.


--
-- TOC entry 4699 (class 0 OID 39197)
-- Dependencies: 236
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
\.


--
-- TOC entry 4763 (class 0 OID 39659)
-- Dependencies: 300
-- Data for Name: spare_asset_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.spare_asset_types (spare_asset_type_id, spare_id, asset_type_id, created_at, updated_at, deleted_at) FROM stdin;
1	1	32	2025-01-28 20:01:59	2025-01-28 20:01:59	\N
2	1	49	2025-01-28 20:01:59	2025-01-28 20:01:59	\N
3	2	32	2025-01-28 20:02:33	2025-01-28 20:02:33	\N
4	3	32	2025-01-28 20:03:14	2025-01-28 20:03:14	\N
5	4	32	2025-01-28 20:03:54	2025-01-28 20:03:54	\N
6	4	49	2025-01-28 20:03:54	2025-01-28 20:03:54	\N
7	5	32	2025-01-28 20:04:31	2025-01-28 20:04:31	\N
8	5	49	2025-01-28 20:04:31	2025-01-28 20:04:31	\N
9	6	32	2025-01-28 20:06:48	2025-01-28 20:06:48	\N
10	7	32	2025-01-28 20:07:29	2025-01-28 20:07:29	\N
11	8	32	2025-01-28 20:08:05	2025-01-28 20:08:05	\N
12	9	32	2025-01-28 20:08:46	2025-01-28 20:08:46	\N
13	9	49	2025-01-28 20:08:46	2025-01-28 20:08:46	\N
14	10	32	2025-01-28 20:09:28	2025-01-28 20:09:28	\N
15	11	32	2025-01-28 20:10:17	2025-01-28 20:10:17	\N
16	12	32	2025-01-28 20:10:56	2025-01-28 20:10:56	\N
17	13	32	2025-01-28 20:11:43	2025-01-28 20:11:43	\N
18	13	49	2025-01-28 20:11:43	2025-01-28 20:11:43	\N
19	14	32	2025-01-28 20:12:25	2025-01-28 20:12:25	\N
20	14	49	2025-01-28 20:12:25	2025-01-28 20:12:25	\N
21	15	32	2025-01-28 20:13:06	2025-01-28 20:13:06	\N
22	16	32	2025-01-28 20:13:41	2025-01-28 20:13:41	\N
23	17	32	2025-01-28 20:14:19	2025-01-28 20:14:19	\N
24	18	32	2025-01-28 20:14:56	2025-01-28 20:14:56	\N
25	18	49	2025-01-28 20:14:56	2025-01-28 20:14:56	\N
26	19	32	2025-01-28 20:15:32	2025-01-28 20:15:32	\N
27	20	32	2025-01-28 20:16:13	2025-01-28 20:16:13	\N
28	21	32	2025-01-28 20:17:03	2025-01-28 20:17:03	\N
29	21	49	2025-01-28 20:17:03	2025-01-28 20:17:03	\N
30	22	32	2025-01-28 20:19:00	2025-01-28 20:19:00	\N
31	22	49	2025-01-28 20:19:00	2025-01-28 20:19:00	\N
32	23	32	2025-01-28 20:20:32	2025-01-28 20:20:32	\N
33	23	49	2025-01-28 20:20:32	2025-01-28 20:20:32	\N
34	24	32	2025-01-28 20:21:25	2025-01-28 20:21:25	\N
35	24	49	2025-01-28 20:21:25	2025-01-28 20:21:25	\N
36	25	32	2025-01-28 20:23:55	2025-01-28 20:23:55	\N
37	25	49	2025-01-28 20:23:55	2025-01-28 20:23:55	\N
38	26	32	2025-01-28 20:24:39	2025-01-28 20:24:39	\N
\.


--
-- TOC entry 4789 (class 0 OID 39858)
-- Dependencies: 326
-- Data for Name: spare_attribute_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.spare_attribute_types (spare_attribute_type_id, spare_attribute_id, spare_type_id, created_at, updated_at, deleted_at) FROM stdin;
1	1	10	2025-01-28 19:06:37	2025-01-28 19:06:37	\N
2	1	18	2025-01-28 19:06:37	2025-01-28 19:06:37	\N
3	1	17	2025-01-28 19:06:37	2025-01-28 19:06:37	\N
4	2	18	2025-01-28 19:08:08	2025-01-28 19:08:08	\N
5	2	17	2025-01-28 19:08:08	2025-01-28 19:08:08	\N
6	2	16	2025-01-28 19:08:08	2025-01-28 19:08:08	\N
7	2	15	2025-01-28 19:08:08	2025-01-28 19:08:08	\N
8	2	14	2025-01-28 19:08:08	2025-01-28 19:08:08	\N
9	3	20	2025-01-28 19:10:45	2025-01-28 19:10:45	\N
10	3	19	2025-01-28 19:10:45	2025-01-28 19:10:45	\N
11	3	18	2025-01-28 19:10:45	2025-01-28 19:10:45	\N
12	3	17	2025-01-28 19:10:45	2025-01-28 19:10:45	\N
13	3	14	2025-01-28 19:10:45	2025-01-28 19:10:45	\N
14	3	13	2025-01-28 19:10:45	2025-01-28 19:10:45	\N
15	3	15	2025-01-28 19:10:45	2025-01-28 19:10:45	\N
16	3	16	2025-01-28 19:10:45	2025-01-28 19:10:45	\N
17	4	20	2025-01-28 19:12:10	2025-01-28 19:12:10	\N
18	4	19	2025-01-28 19:12:10	2025-01-28 19:12:10	\N
19	4	18	2025-01-28 19:12:10	2025-01-28 19:12:10	\N
20	4	17	2025-01-28 19:12:10	2025-01-28 19:12:10	\N
21	4	16	2025-01-28 19:12:10	2025-01-28 19:12:10	\N
\.


--
-- TOC entry 4809 (class 0 OID 40013)
-- Dependencies: 346
-- Data for Name: spare_attribute_values; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.spare_attribute_values (spare_attribute_value_id, spare_id, spare_attribute_id, field_value, created_at, updated_at, deleted_at) FROM stdin;
1	1	1		2025-01-28 20:01:59	2025-01-28 20:01:59	\N
2	1	2		2025-01-28 20:01:59	2025-01-28 20:01:59	\N
3	1	3	xyz	2025-01-28 20:01:59	2025-01-28 20:01:59	\N
4	1	4	Pieces	2025-01-28 20:01:59	2025-01-28 20:01:59	\N
5	2	1		2025-01-28 20:02:33	2025-01-28 20:02:33	\N
6	2	2		2025-01-28 20:02:33	2025-01-28 20:02:33	\N
7	2	3		2025-01-28 20:02:33	2025-01-28 20:02:33	\N
8	2	4	Pieces	2025-01-28 20:02:34	2025-01-28 20:02:34	\N
9	3	1		2025-01-28 20:03:14	2025-01-28 20:03:14	\N
10	3	2		2025-01-28 20:03:14	2025-01-28 20:03:14	\N
11	3	3	xyz	2025-01-28 20:03:14	2025-01-28 20:03:14	\N
12	3	4	Pieces	2025-01-28 20:03:14	2025-01-28 20:03:14	\N
13	4	1		2025-01-28 20:03:54	2025-01-28 20:03:54	\N
14	4	2		2025-01-28 20:03:54	2025-01-28 20:03:54	\N
15	4	3	xyz	2025-01-28 20:03:54	2025-01-28 20:03:54	\N
16	4	4	Pieces	2025-01-28 20:03:54	2025-01-28 20:03:54	\N
17	5	1		2025-01-28 20:04:31	2025-01-28 20:04:31	\N
18	5	2		2025-01-28 20:04:31	2025-01-28 20:04:31	\N
19	5	3	xyz	2025-01-28 20:04:31	2025-01-28 20:04:31	\N
20	5	4	Pieces	2025-01-28 20:04:31	2025-01-28 20:04:31	\N
21	6	1		2025-01-28 20:06:48	2025-01-28 20:06:48	\N
22	6	2		2025-01-28 20:06:48	2025-01-28 20:06:48	\N
23	6	3	xyz	2025-01-28 20:06:48	2025-01-28 20:06:48	\N
24	6	4	Pieces	2025-01-28 20:06:48	2025-01-28 20:06:48	\N
25	7	1		2025-01-28 20:07:29	2025-01-28 20:07:29	\N
26	7	2		2025-01-28 20:07:29	2025-01-28 20:07:29	\N
27	7	3	xyz	2025-01-28 20:07:29	2025-01-28 20:07:29	\N
28	7	4	Pieces	2025-01-28 20:07:29	2025-01-28 20:07:29	\N
29	8	1		2025-01-28 20:08:05	2025-01-28 20:08:05	\N
30	8	2		2025-01-28 20:08:05	2025-01-28 20:08:05	\N
31	8	3	xyz	2025-01-28 20:08:05	2025-01-28 20:08:05	\N
32	8	4	Pieces	2025-01-28 20:08:05	2025-01-28 20:08:05	\N
33	9	1		2025-01-28 20:08:46	2025-01-28 20:08:46	\N
34	9	2		2025-01-28 20:08:46	2025-01-28 20:08:46	\N
35	9	3	xyz	2025-01-28 20:08:46	2025-01-28 20:08:46	\N
36	9	4	Pieces	2025-01-28 20:08:46	2025-01-28 20:08:46	\N
37	10	1		2025-01-28 20:09:28	2025-01-28 20:09:28	\N
38	10	2		2025-01-28 20:09:28	2025-01-28 20:09:28	\N
39	10	3	xyz	2025-01-28 20:09:28	2025-01-28 20:09:28	\N
40	10	4	Pieces	2025-01-28 20:09:28	2025-01-28 20:09:28	\N
41	11	1		2025-01-28 20:10:17	2025-01-28 20:10:17	\N
42	11	2		2025-01-28 20:10:17	2025-01-28 20:10:17	\N
43	11	3	xyz	2025-01-28 20:10:17	2025-01-28 20:10:17	\N
44	11	4	Pieces	2025-01-28 20:10:17	2025-01-28 20:10:17	\N
45	12	1		2025-01-28 20:10:56	2025-01-28 20:10:56	\N
46	12	2		2025-01-28 20:10:56	2025-01-28 20:10:56	\N
47	12	3	xyz	2025-01-28 20:10:56	2025-01-28 20:10:56	\N
48	12	4	Pieces	2025-01-28 20:10:56	2025-01-28 20:10:56	\N
49	13	1		2025-01-28 20:11:43	2025-01-28 20:11:43	\N
50	13	2		2025-01-28 20:11:43	2025-01-28 20:11:43	\N
51	13	3	xyz	2025-01-28 20:11:43	2025-01-28 20:11:43	\N
52	13	4	Pieces	2025-01-28 20:11:43	2025-01-28 20:11:43	\N
53	14	1		2025-01-28 20:12:25	2025-01-28 20:12:25	\N
54	14	2		2025-01-28 20:12:25	2025-01-28 20:12:25	\N
55	14	3	xyz	2025-01-28 20:12:25	2025-01-28 20:12:25	\N
56	14	4	Pieces	2025-01-28 20:12:25	2025-01-28 20:12:25	\N
57	15	1		2025-01-28 20:13:06	2025-01-28 20:13:06	\N
58	15	2		2025-01-28 20:13:06	2025-01-28 20:13:06	\N
59	15	3	xyz	2025-01-28 20:13:06	2025-01-28 20:13:06	\N
60	15	4	Pieces	2025-01-28 20:13:06	2025-01-28 20:13:06	\N
61	16	1		2025-01-28 20:13:41	2025-01-28 20:13:41	\N
62	16	2		2025-01-28 20:13:41	2025-01-28 20:13:41	\N
63	16	3	xyz	2025-01-28 20:13:41	2025-01-28 20:13:41	\N
64	16	4	Pieces	2025-01-28 20:13:41	2025-01-28 20:13:41	\N
65	17	1		2025-01-28 20:14:19	2025-01-28 20:14:19	\N
66	17	2		2025-01-28 20:14:19	2025-01-28 20:14:19	\N
67	17	3	xyz	2025-01-28 20:14:19	2025-01-28 20:14:19	\N
68	17	4	Pieces	2025-01-28 20:14:19	2025-01-28 20:14:19	\N
69	18	1		2025-01-28 20:14:56	2025-01-28 20:14:56	\N
70	18	2		2025-01-28 20:14:56	2025-01-28 20:14:56	\N
71	18	3	xyz	2025-01-28 20:14:56	2025-01-28 20:14:56	\N
72	18	4	Pieces	2025-01-28 20:14:56	2025-01-28 20:14:56	\N
73	19	1		2025-01-28 20:15:32	2025-01-28 20:15:32	\N
74	19	2		2025-01-28 20:15:32	2025-01-28 20:15:32	\N
75	19	3	xyz	2025-01-28 20:15:32	2025-01-28 20:15:32	\N
76	19	4	Pieces	2025-01-28 20:15:32	2025-01-28 20:15:32	\N
77	20	1		2025-01-28 20:16:13	2025-01-28 20:16:13	\N
78	20	2		2025-01-28 20:16:13	2025-01-28 20:16:13	\N
79	20	3	xyz	2025-01-28 20:16:13	2025-01-28 20:16:13	\N
80	20	4	Pieces	2025-01-28 20:16:13	2025-01-28 20:16:13	\N
81	21	1		2025-01-28 20:17:03	2025-01-28 20:17:03	\N
82	21	2		2025-01-28 20:17:03	2025-01-28 20:17:03	\N
83	21	3	xyz	2025-01-28 20:17:03	2025-01-28 20:17:03	\N
84	21	4	Pieces	2025-01-28 20:17:04	2025-01-28 20:17:04	\N
85	22	1		2025-01-28 20:19:00	2025-01-28 20:19:00	\N
86	22	2		2025-01-28 20:19:00	2025-01-28 20:19:00	\N
87	22	3	ACC	2025-01-28 20:19:00	2025-01-28 20:19:00	\N
88	22	4	Ltr	2025-01-28 20:19:00	2025-01-28 20:19:00	\N
89	23	1		2025-01-28 20:20:32	2025-01-28 20:20:32	\N
90	23	2		2025-01-28 20:20:32	2025-01-28 20:20:32	\N
91	23	3	ACC	2025-01-28 20:20:32	2025-01-28 20:20:32	\N
92	23	4	Kg	2025-01-28 20:20:32	2025-01-28 20:20:32	\N
93	24	1		2025-01-28 20:21:25	2025-01-28 20:21:25	\N
94	24	2		2025-01-28 20:21:25	2025-01-28 20:21:25	\N
95	24	3	DALMIA	2025-01-28 20:21:25	2025-01-28 20:21:25	\N
96	24	4	Kg	2025-01-28 20:21:25	2025-01-28 20:21:25	\N
97	25	1		2025-01-28 20:23:55	2025-01-28 20:23:55	\N
98	25	2		2025-01-28 20:23:55	2025-01-28 20:23:55	\N
99	25	3	DALMIA	2025-01-28 20:23:55	2025-01-28 20:23:55	\N
100	25	4	Kg	2025-01-28 20:23:55	2025-01-28 20:23:55	\N
101	26	1		2025-01-28 20:24:39	2025-01-28 20:24:39	\N
102	26	2		2025-01-28 20:24:39	2025-01-28 20:24:39	\N
103	26	3	xyz	2025-01-28 20:24:39	2025-01-28 20:24:39	\N
104	26	4	Pieces	2025-01-28 20:24:39	2025-01-28 20:24:39	\N
\.


--
-- TOC entry 4779 (class 0 OID 39783)
-- Dependencies: 316
-- Data for Name: spare_attributes; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.spare_attributes (spare_attribute_id, field_name, display_name, field_type, field_values, field_length, is_required, user_id, created_at, updated_at, deleted_at, list_parameter_id) FROM stdin;
1	PieceWeight	Piece Weight (kg/pc)	Number	\N	10	f	1	2025-01-28 19:06:37	2025-01-28 19:06:37	\N	\N
2	spares_color	spares_color	Color	\N	50	f	1	2025-01-28 19:08:08	2025-01-28 19:08:08	\N	\N
3	spares_make	Spares Make (OEM)	Text	\N	15	f	1	2025-01-28 19:10:45	2025-01-28 19:10:45	\N	\N
4	spares_uom	Unit of Measurement	List	\N	10	t	1	2025-01-28 19:12:10	2025-01-28 19:12:10	\N	4
\.


--
-- TOC entry 4713 (class 0 OID 39258)
-- Dependencies: 250
-- Data for Name: spare_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.spare_types (spare_type_id, spare_type_code, spare_type_name, created_at, updated_at, deleted_at) FROM stdin;
1	WSHR	Washer	2025-01-28 18:38:38	2025-01-28 18:38:38	\N
2	GSKT	Gaskets	2025-01-28 18:38:47	2025-01-28 18:38:47	\N
3	BOLTS	Bolts	2025-01-28 18:38:57	2025-01-28 18:38:57	\N
4	KEYS	KEYS	2025-01-28 18:39:07	2025-01-28 18:39:07	\N
5	SPRINGS	Springs	2025-01-28 18:39:18	2025-01-28 18:39:18	\N
6	LOCKS	Locks	2025-01-28 18:39:32	2025-01-28 18:39:32	\N
7	CHNL	Channels	2025-01-28 18:39:42	2025-01-28 18:39:42	\N
8	FRAMES	Frames	2025-01-28 18:39:57	2025-01-28 18:39:57	\N
9	BELTS	Belts	2025-01-28 18:40:12	2025-01-28 18:40:12	\N
10	PLATE	Plates	2025-01-28 18:40:24	2025-01-28 18:40:24	\N
11	PLUGS	Plugs	2025-01-28 18:40:35	2025-01-28 18:40:35	\N
12	SEALS	Seals	2025-01-28 18:40:47	2025-01-28 18:40:47	\N
13	GRBOX	Gear Box	2025-01-28 18:41:03	2025-01-28 18:41:03	\N
14	REF_PRECAST	Refractory PreCast Blocks	2025-01-28 18:41:23	2025-01-28 18:41:23	\N
15	MOTOR_LT	Motor Low Tension Type	2025-01-28 18:41:34	2025-01-28 18:41:34	\N
16	MOTOR_HT	Motor High Tension Type	2025-01-28 18:42:00	2025-01-28 18:42:00	\N
17	REF_CASTABLE	Refractory Castable Type	2025-01-28 18:42:13	2025-01-28 18:42:13	\N
18	REF_BRICKS	Refractory Bricks	2025-01-28 18:42:32	2025-01-28 18:42:32	\N
19	FLANGES	Flanges Connection	2025-01-28 18:42:45	2025-01-28 18:42:45	\N
20	BEARING	Bearing - Different types	2025-01-28 18:42:58	2025-01-28 18:42:58	\N
\.


--
-- TOC entry 4729 (class 0 OID 39353)
-- Dependencies: 266
-- Data for Name: spares; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.spares (spare_id, spare_type_id, spare_code, spare_name, created_at, updated_at, deleted_at) FROM stdin;
1	18	REF_BRICKS_AI2O3-40%	Al2O3-40-50 %	2025-01-28 20:01:59	2025-01-28 20:01:59	\N
2	18	REF_BRICKS_AI2O3-60%	Al2O3-60 %	2025-01-28 20:02:33	2025-01-28 20:02:33	\N
3	18	REF_BRICKS_AI2O3-70%	Al2O3-70-75%	2025-01-28 20:03:14	2025-01-28 20:03:14	\N
4	18	REF_BRICKS_REFRAMAG AF	Reframag AF	2025-01-28 20:03:54	2025-01-28 20:03:54	\N
5	18	REF_BRICKS_REFRA-THERM	Refra-therm	2025-01-28 20:04:31	2025-01-28 20:04:31	\N
6	18	REF_BRICKS_TOPMAG A1	Topmag A1	2025-01-28 20:06:48	2025-01-28 20:06:48	\N
7	18	REF_BRICKS_ANKRAL R1	Ankral R1	2025-01-28 20:07:29	2025-01-28 20:07:29	\N
8	18	REF_BRICKS_ALMAG-AF	Almag-AF	2025-01-28 20:08:05	2025-01-28 20:08:05	\N
9	18	REF_BRICKS_ANKRAL ZE	Ankral ZE	2025-01-28 20:08:46	2025-01-28 20:08:46	\N
10	18	REF_BRICKS_FERROMAG 90	Ferromag 90	2025-01-28 20:09:28	2025-01-28 20:09:28	\N
11	18	REF_BRICKS_PERILEX 83	Perilex 83	2025-01-28 20:10:17	2025-01-28 20:10:17	\N
12	18	REF_BRICKS_ANKRAL SE	Ankral SE	2025-01-28 20:10:56	2025-01-28 20:10:56	\N
13	18	REF_BRICKS_MAGNUM	Magnum	2025-01-28 20:11:43	2025-01-28 20:11:43	\N
14	18	REF_BRICKS_TOPMAG AF	Topmag AF	2025-01-28 20:12:25	2025-01-28 20:12:25	\N
15	18	REF_BRICKS_ALMAG AF	Almag AF	2025-01-28 20:13:06	2025-01-28 20:13:06	\N
16	18	REF_BRICKS_ANKRAL R2	Ankral R2	2025-01-28 20:13:41	2025-01-28 20:13:41	\N
17	18	REF_BRICKS_ALMAG-85	Almag-85	2025-01-28 20:14:19	2025-01-28 20:14:19	\N
18	18	REF_BRICKS_KRONEX -85	Kronex -85	2025-01-28 20:14:56	2025-01-28 20:14:56	\N
19	18	REF_BRICKS_ALMAG A1	Almag A1	2025-01-28 20:15:32	2025-01-28 20:15:32	\N
20	18	REF_BRICKS_ANKRAL Z1	Ankral Z1	2025-01-28 20:16:13	2025-01-28 20:16:13	\N
21	18	REF_BRICKS_FERROMAG F1	Ferromag F1	2025-01-28 20:17:03	2025-01-28 20:17:03	\N
22	18	REF_CASTABLE_SEALANT	Sealant Paste	2025-01-28 20:19:00	2025-01-28 20:19:00	\N
23	17	REF_CASTABLE_BASE	Base Castable Powder	2025-01-28 20:20:32	2025-01-28 20:20:32	\N
24	18	REF_BRICKS_ZIRCONIA	Zirconia Bricks	2025-01-28 20:21:25	2025-01-28 20:21:25	\N
25	18	REF_BRICKS_MAGNESIA	Magnesia Bricks	2025-01-28 20:23:55	2025-01-28 20:23:55	\N
26	18	REF_BRICKS_PERILEX CF	Perilex CF	2025-01-28 20:24:39	2025-01-28 20:24:39	\N
\.


--
-- TOC entry 4859 (class 0 OID 40716)
-- Dependencies: 396
-- Data for Name: template_attribute_values; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.template_attribute_values (template_attribute_value_id, asset_template_id, asset_attribute_id, field_value, created_at, updated_at, deleted_at) FROM stdin;
1	1	2	x	2025-01-28 21:10:52	2025-01-28 21:10:52	\N
2	1	3	Vendor1	2025-01-28 21:10:52	2025-01-28 21:10:52	\N
3	1	4	x	2025-01-28 21:10:52	2025-01-28 21:10:52	\N
4	1	5	x	2025-01-28 21:10:52	2025-01-28 21:10:52	\N
5	1	6	x	2025-01-28 21:10:52	2025-01-28 21:10:52	\N
6	1	7	1800	2025-01-28 21:10:52	2025-01-28 21:10:52	\N
7	1	8	x	2025-01-28 21:10:52	2025-01-28 21:10:52	\N
8	1	9		2025-01-28 21:10:52	2025-01-28 21:10:52	\N
9	1	10	xx	2025-01-28 21:10:52	2025-01-28 21:10:52	\N
\.


--
-- TOC entry 4877 (class 0 OID 41009)
-- Dependencies: 414
-- Data for Name: template_datasource_values; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.template_datasource_values (template_datasource_value_id, asset_template_id, asset_template_datasource_id, template_zone_id, data_source_id, data_source_attribute_id, field_value, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 4857 (class 0 OID 40699)
-- Dependencies: 394
-- Data for Name: template_departments; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.template_departments (template_department_id, asset_template_id, department_id, created_at, updated_at) FROM stdin;
1	1	1	2025-01-28 21:10:52	2025-01-28 21:10:52
\.


--
-- TOC entry 4869 (class 0 OID 40871)
-- Dependencies: 406
-- Data for Name: template_service_values; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.template_service_values (template_service_value_id, asset_template_service_id, asset_template_id, service_id, template_zone_id, service_attribute_id, field_value, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 4863 (class 0 OID 40770)
-- Dependencies: 400
-- Data for Name: template_spare_values; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.template_spare_values (template_spare_value_id, spare_id, asset_template_id, asset_template_spare_id, template_zone_id, spare_attribute_id, field_value, created_at, updated_at, deleted_at) FROM stdin;
\.


--
-- TOC entry 4873 (class 0 OID 40940)
-- Dependencies: 410
-- Data for Name: template_variable_values; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.template_variable_values (template_variable_value_id, asset_template_variable_id, asset_template_id, variable_id, template_zone_id, variable_attribute_id, field_value, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 4855 (class 0 OID 40686)
-- Dependencies: 392
-- Data for Name: template_zones; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.template_zones (template_zone_id, asset_template_id, zone_name, height, diameter, created_at, updated_at) FROM stdin;
1	1	Overall	1.00	5.00	2025-01-28 21:10:52	2025-01-28 21:10:52
2	1	HotZone	5.00	5.00	2025-01-28 21:10:52	2025-01-28 21:10:52
3	1	Bottom	9.00	5.00	2025-01-28 21:10:52	2025-01-28 21:10:52
\.


--
-- TOC entry 4741 (class 0 OID 39440)
-- Dependencies: 278
-- Data for Name: user_activities; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.user_activities (user_activity_id, activity_no, activity_date, user_id, asset_id, status, reason_id, cost, note, created_at, updated_at, activity_status, plant_id) FROM stdin;
\.


--
-- TOC entry 4749 (class 0 OID 39539)
-- Dependencies: 286
-- Data for Name: user_asset_checks; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.user_asset_checks (user_asset_check_id, user_check_id, check_id, asset_check_id, field_name, field_type, default_value, is_required, lcl, ucl, field_values, "order", value, created_at, updated_at, remark_user_id, remark_status, remarks, remark_date) FROM stdin;
\.


--
-- TOC entry 4835 (class 0 OID 40397)
-- Dependencies: 372
-- Data for Name: user_asset_variables; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.user_asset_variables (user_asset_variable_id, user_variable_id, variable_id, value, created_at, updated_at, asset_zone_id) FROM stdin;
\.


--
-- TOC entry 4751 (class 0 OID 39564)
-- Dependencies: 288
-- Data for Name: user_check_attachments; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.user_check_attachments (user_check_attachment_id, user_check_id, attachments, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 4747 (class 0 OID 39515)
-- Dependencies: 284
-- Data for Name: user_checks; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.user_checks (user_check_id, plant_id, user_id, asset_id, reference_no, reference_date, note, created_at, updated_at, asset_zone_id, department_id) FROM stdin;
\.


--
-- TOC entry 4743 (class 0 OID 39469)
-- Dependencies: 280
-- Data for Name: user_services; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.user_services (user_service_id, plant_id, user_id, service_no, asset_id, service_date, next_service_date, note, created_at, updated_at, is_latest) FROM stdin;
\.


--
-- TOC entry 4745 (class 0 OID 39498)
-- Dependencies: 282
-- Data for Name: user_spares; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.user_spares (user_spare_id, user_service_id, spare_id, spare_cost, created_at, updated_at, service_id, asset_zone_id, service_cost, quantity) FROM stdin;
\.


--
-- TOC entry 4833 (class 0 OID 40368)
-- Dependencies: 370
-- Data for Name: user_variables; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.user_variables (user_variable_id, plant_id, user_id, asset_id, job_no, job_date, note, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 4696 (class 0 OID 39170)
-- Dependencies: 233
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (user_id, name, email, password, mobile_no, role_id, plant_id, address, avatar, created_at, updated_at, deleted_at, department_id) FROM stdin;
1	Bharatesh Shanawad	bharatesh.s@akxatech.com	$2y$12$jxwr0PIYlSn76SB08HdNJeqkqckow3Dc/tXszx.EAVMmLGh3OoHR2	9535342875	1	1	\N	\N	2025-01-28 18:04:18	2025-01-28 18:04:18	\N	\N
2	AKXA Admin	admin@akxatech.com	$2y$12$fD1TU2wQro/T8iprMt5/iukSpYxbKkZ.bY8flrMdCwzhNZ1nfCwPe	9243222222	1	1	\N	\N	2025-01-28 18:11:45	2025-01-28 18:11:45	\N	7
3	Gaurav Patil	gaurav.patil@jsw.in	$2y$12$fU.6Koo58XrI9P2B8VusEu7G1Be.vP78SAv6QaClzdINyVDTna9TK	9243569870	1	1	\N	\N	2025-01-28 18:12:18	2025-01-28 18:12:18	\N	7
\.


--
-- TOC entry 4801 (class 0 OID 39955)
-- Dependencies: 338
-- Data for Name: variable_asset_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.variable_asset_types (variable_asset_type_id, variable_id, asset_type_id, created_at, updated_at, deleted_at) FROM stdin;
1	1	36	2025-01-28 20:35:23	2025-01-28 20:35:23	\N
2	1	13	2025-01-28 20:35:23	2025-01-28 20:35:23	\N
3	1	15	2025-01-28 20:35:23	2025-01-28 20:35:23	\N
4	2	10	2025-01-28 20:37:01	2025-01-28 20:37:01	\N
5	2	11	2025-01-28 20:37:01	2025-01-28 20:37:01	\N
6	2	32	2025-01-28 20:37:01	2025-01-28 20:37:01	\N
7	2	29	2025-01-28 20:37:01	2025-01-28 20:37:01	\N
8	3	16	2025-01-28 20:39:49	2025-01-28 20:39:49	\N
9	3	32	2025-01-28 20:39:49	2025-01-28 20:39:49	\N
10	3	29	2025-01-28 20:39:49	2025-01-28 20:39:49	\N
11	4	32	2025-01-28 20:41:24	2025-01-28 20:41:24	\N
12	4	29	2025-01-28 20:41:24	2025-01-28 20:41:24	\N
13	5	32	2025-01-28 20:43:50	2025-01-28 20:43:50	\N
14	5	29	2025-01-28 20:43:50	2025-01-28 20:43:50	\N
15	6	32	2025-01-28 20:45:34	2025-01-28 20:45:34	\N
16	6	29	2025-01-28 20:45:34	2025-01-28 20:45:34	\N
17	7	32	2025-01-28 20:47:44	2025-01-28 20:47:44	\N
18	7	29	2025-01-28 20:47:44	2025-01-28 20:47:44	\N
19	8	32	2025-01-28 20:49:26	2025-01-28 20:49:26	\N
20	8	29	2025-01-28 20:49:26	2025-01-28 20:49:26	\N
21	8	32	2025-01-28 20:49:26	2025-01-28 20:49:26	\N
22	8	29	2025-01-28 20:49:26	2025-01-28 20:49:26	\N
23	9	32	2025-01-28 20:50:45	2025-01-28 20:50:45	\N
24	9	29	2025-01-28 20:50:45	2025-01-28 20:50:45	\N
25	10	32	2025-01-28 20:52:14	2025-01-28 20:52:14	\N
26	10	29	2025-01-28 20:52:14	2025-01-28 20:52:14	\N
27	11	32	2025-01-28 20:53:45	2025-01-28 20:53:45	\N
28	11	29	2025-01-28 20:53:45	2025-01-28 20:53:45	\N
29	12	32	2025-01-28 20:55:11	2025-01-28 20:55:11	\N
30	12	29	2025-01-28 20:55:11	2025-01-28 20:55:11	\N
31	13	32	2025-01-28 20:57:02	2025-01-28 20:57:02	\N
32	13	29	2025-01-28 20:57:02	2025-01-28 20:57:02	\N
33	13	32	2025-01-28 20:57:02	2025-01-28 20:57:02	\N
34	13	29	2025-01-28 20:57:02	2025-01-28 20:57:02	\N
35	14	32	2025-01-28 20:58:27	2025-01-28 20:58:27	\N
36	14	29	2025-01-28 20:58:27	2025-01-28 20:58:27	\N
37	15	32	2025-01-28 20:59:40	2025-01-28 20:59:40	\N
38	15	29	2025-01-28 20:59:40	2025-01-28 20:59:40	\N
39	16	32	2025-01-28 21:00:52	2025-01-28 21:00:52	\N
40	16	29	2025-01-28 21:00:52	2025-01-28 21:00:52	\N
41	16	32	2025-01-28 21:00:52	2025-01-28 21:00:52	\N
42	16	29	2025-01-28 21:00:52	2025-01-28 21:00:52	\N
43	17	32	2025-01-28 21:02:46	2025-01-28 21:02:46	\N
44	18	32	2025-01-28 21:04:06	2025-01-28 21:04:06	\N
45	18	29	2025-01-28 21:04:06	2025-01-28 21:04:06	\N
46	18	49	2025-01-28 21:04:06	2025-01-28 21:04:06	\N
\.


--
-- TOC entry 4793 (class 0 OID 39892)
-- Dependencies: 330
-- Data for Name: variable_attribute_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.variable_attribute_types (variable_attribute_type_id, variable_attribute_id, variable_type_id, created_at, updated_at, deleted_at) FROM stdin;
1	1	6	2025-01-28 19:17:30	2025-01-28 19:17:30	\N
2	1	5	2025-01-28 19:17:30	2025-01-28 19:17:30	\N
3	1	4	2025-01-28 19:17:30	2025-01-28 19:17:30	\N
4	1	3	2025-01-28 19:17:30	2025-01-28 19:17:30	\N
5	2	1	2025-01-28 19:18:53	2025-01-28 19:18:53	\N
6	2	2	2025-01-28 19:18:53	2025-01-28 19:18:53	\N
7	2	3	2025-01-28 19:18:53	2025-01-28 19:18:53	\N
8	2	4	2025-01-28 19:18:53	2025-01-28 19:18:53	\N
9	2	5	2025-01-28 19:18:53	2025-01-28 19:18:53	\N
10	2	6	2025-01-28 19:18:53	2025-01-28 19:18:53	\N
11	3	3	2025-01-28 19:19:36	2025-01-28 19:19:36	\N
12	3	4	2025-01-28 19:19:36	2025-01-28 19:19:36	\N
13	3	5	2025-01-28 19:19:36	2025-01-28 19:19:36	\N
14	3	6	2025-01-28 19:19:36	2025-01-28 19:19:36	\N
15	4	3	2025-01-28 19:20:19	2025-01-28 19:20:19	\N
16	4	4	2025-01-28 19:20:19	2025-01-28 19:20:19	\N
17	4	5	2025-01-28 19:20:19	2025-01-28 19:20:19	\N
18	4	6	2025-01-28 19:20:19	2025-01-28 19:20:19	\N
19	5	3	2025-01-28 19:20:51	2025-01-28 19:20:51	\N
20	5	4	2025-01-28 19:20:51	2025-01-28 19:20:51	\N
21	5	5	2025-01-28 19:20:51	2025-01-28 19:20:51	\N
22	5	6	2025-01-28 19:20:51	2025-01-28 19:20:51	\N
23	6	3	2025-01-28 19:21:39	2025-01-28 19:21:39	\N
24	6	4	2025-01-28 19:21:39	2025-01-28 19:21:39	\N
25	6	5	2025-01-28 19:21:39	2025-01-28 19:21:39	\N
26	6	6	2025-01-28 19:21:39	2025-01-28 19:21:39	\N
27	7	1	2025-01-28 19:22:19	2025-01-28 19:22:19	\N
28	7	2	2025-01-28 19:22:19	2025-01-28 19:22:19	\N
29	7	3	2025-01-28 19:22:19	2025-01-28 19:22:19	\N
30	7	4	2025-01-28 19:22:19	2025-01-28 19:22:19	\N
31	7	5	2025-01-28 19:22:19	2025-01-28 19:22:19	\N
32	7	6	2025-01-28 19:22:19	2025-01-28 19:22:19	\N
\.


--
-- TOC entry 4813 (class 0 OID 40047)
-- Dependencies: 350
-- Data for Name: variable_attribute_values; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.variable_attribute_values (variable_attribute_value_id, variable_id, variable_attribute_id, field_value, created_at, updated_at, deleted_at) FROM stdin;
1	1	1	1600	2025-01-28 20:35:23	2025-01-28 20:35:23	\N
2	1	2	1500	2025-01-28 20:35:23	2025-01-28 20:35:23	\N
3	1	3	Tip	2025-01-28 20:35:23	2025-01-28 20:35:23	\N
4	1	4	1700	2025-01-28 20:35:23	2025-01-28 20:35:23	\N
5	1	5	100	2025-01-28 20:35:23	2025-01-28 20:35:23	\N
6	1	6	degC	2025-01-28 20:35:23	2025-01-28 20:35:23	\N
7	1	7	Temperature	2025-01-28 20:35:23	2025-01-28 20:35:23	\N
8	2	1	1650	2025-01-28 20:37:01	2025-01-28 20:37:01	\N
9	2	2	1450	2025-01-28 20:37:01	2025-01-28 20:37:01	\N
10	2	3	Tip	2025-01-28 20:37:01	2025-01-28 20:37:01	\N
11	2	4	1700	2025-01-28 20:37:01	2025-01-28 20:37:01	\N
12	2	5	100	2025-01-28 20:37:01	2025-01-28 20:37:01	\N
13	2	6	degC	2025-01-28 20:37:01	2025-01-28 20:37:01	\N
14	2	7	Temperature	2025-01-28 20:37:01	2025-01-28 20:37:01	\N
15	3	1	3	2025-01-28 20:39:49	2025-01-28 20:39:49	\N
16	3	2	2	2025-01-28 20:39:49	2025-01-28 20:39:49	\N
17	3	3	Dipped	2025-01-28 20:39:49	2025-01-28 20:39:49	\N
18	3	4	5	2025-01-28 20:39:49	2025-01-28 20:39:49	\N
19	3	5	0.1	2025-01-28 20:39:49	2025-01-28 20:39:49	\N
20	3	6	%	2025-01-28 20:39:49	2025-01-28 20:39:49	\N
21	3	7	Concentration	2025-01-28 20:39:49	2025-01-28 20:39:49	\N
22	4	1	1050	2025-01-28 20:41:24	2025-01-28 20:41:24	\N
23	4	2	900	2025-01-28 20:41:24	2025-01-28 20:41:24	\N
24	4	3	x	2025-01-28 20:41:24	2025-01-28 20:41:24	\N
25	4	4	1500	2025-01-28 20:41:24	2025-01-28 20:41:24	\N
26	4	5	30	2025-01-28 20:41:24	2025-01-28 20:41:24	\N
27	4	6	degC	2025-01-28 20:41:24	2025-01-28 20:41:24	\N
28	4	7	Temperature	2025-01-28 20:41:24	2025-01-28 20:41:24	\N
29	5	1	30	2025-01-28 20:43:50	2025-01-28 20:43:50	\N
30	5	2	20	2025-01-28 20:43:50	2025-01-28 20:43:50	\N
31	5	3	x	2025-01-28 20:43:50	2025-01-28 20:43:50	\N
32	5	4	100	2025-01-28 20:43:50	2025-01-28 20:43:50	\N
33	5	5	0	2025-01-28 20:43:50	2025-01-28 20:43:50	\N
34	5	6	Hrs	2025-01-28 20:43:50	2025-01-28 20:43:50	\N
35	5	7	Time	2025-01-28 20:43:50	2025-01-28 20:43:50	\N
36	6	1	50	2025-01-28 20:45:34	2025-01-28 20:45:34	\N
37	6	2	20	2025-01-28 20:45:34	2025-01-28 20:45:34	\N
38	6	3	x	2025-01-28 20:45:34	2025-01-28 20:45:34	\N
39	6	4	100	2025-01-28 20:45:34	2025-01-28 20:45:34	\N
40	6	5	0	2025-01-28 20:45:34	2025-01-28 20:45:34	\N
41	6	6	Hrs	2025-01-28 20:45:34	2025-01-28 20:45:34	\N
42	6	7	Time	2025-01-28 20:45:34	2025-01-28 20:45:34	\N
43	7	1	22	2025-01-28 20:47:44	2025-01-28 20:47:44	\N
44	7	2	20	2025-01-28 20:47:44	2025-01-28 20:47:44	\N
45	7	3	x	2025-01-28 20:47:44	2025-01-28 20:47:44	\N
46	7	4	25	2025-01-28 20:47:44	2025-01-28 20:47:44	\N
47	7	5	0	2025-01-28 20:47:44	2025-01-28 20:47:44	\N
48	7	6	ton	2025-01-28 20:47:44	2025-01-28 20:47:44	\N
49	7	7	Count	2025-01-28 20:47:44	2025-01-28 20:47:44	\N
50	8	1	120	2025-01-28 20:49:26	2025-01-28 20:49:26	\N
51	8	2	100	2025-01-28 20:49:26	2025-01-28 20:49:26	\N
52	8	3	x	2025-01-28 20:49:26	2025-01-28 20:49:26	\N
53	8	4	250	2025-01-28 20:49:26	2025-01-28 20:49:26	\N
54	8	5	0	2025-01-28 20:49:26	2025-01-28 20:49:26	\N
55	8	6	Kg	2025-01-28 20:49:26	2025-01-28 20:49:26	\N
56	8	7	Count	2025-01-28 20:49:26	2025-01-28 20:49:26	\N
57	9	1	60	2025-01-28 20:50:45	2025-01-28 20:50:45	\N
58	9	2	50	2025-01-28 20:50:45	2025-01-28 20:50:45	\N
59	9	3	x	2025-01-28 20:50:45	2025-01-28 20:50:45	\N
60	9	4	120	2025-01-28 20:50:45	2025-01-28 20:50:45	\N
61	9	5	0	2025-01-28 20:50:45	2025-01-28 20:50:45	\N
62	9	6	Kg	2025-01-28 20:50:45	2025-01-28 20:50:45	\N
63	9	7	Count	2025-01-28 20:50:45	2025-01-28 20:50:45	\N
64	10	1	30	2025-01-28 20:52:14	2025-01-28 20:52:14	\N
65	10	2	20	2025-01-28 20:52:14	2025-01-28 20:52:14	\N
66	10	3	x	2025-01-28 20:52:14	2025-01-28 20:52:14	\N
67	10	4	50	2025-01-28 20:52:14	2025-01-28 20:52:14	\N
68	10	5	0	2025-01-28 20:52:14	2025-01-28 20:52:14	\N
69	10	6	Kg	2025-01-28 20:52:14	2025-01-28 20:52:14	\N
70	10	7	Count	2025-01-28 20:52:14	2025-01-28 20:52:14	\N
71	11	1	0.5	2025-01-28 20:53:45	2025-01-28 20:53:45	\N
72	11	2	0.2	2025-01-28 20:53:45	2025-01-28 20:53:45	\N
73	11	3	x	2025-01-28 20:53:45	2025-01-28 20:53:45	\N
74	11	4	1	2025-01-28 20:53:45	2025-01-28 20:53:45	\N
75	11	5	0	2025-01-28 20:53:45	2025-01-28 20:53:45	\N
76	11	6	%	2025-01-28 20:53:45	2025-01-28 20:53:45	\N
77	11	7	Concentration	2025-01-28 20:53:45	2025-01-28 20:53:45	\N
78	12	1	1500	2025-01-28 20:55:11	2025-01-28 20:55:11	\N
79	12	2	1200	2025-01-28 20:55:11	2025-01-28 20:55:11	\N
80	12	3	x	2025-01-28 20:55:11	2025-01-28 20:55:11	\N
81	12	4	1700	2025-01-28 20:55:11	2025-01-28 20:55:11	\N
82	12	5	0	2025-01-28 20:55:11	2025-01-28 20:55:11	\N
83	12	6	degC	2025-01-28 20:55:11	2025-01-28 20:55:11	\N
84	12	7	Temperature	2025-01-28 20:55:11	2025-01-28 20:55:11	\N
85	13	1	0.75	2025-01-28 20:57:02	2025-01-28 20:57:02	\N
86	13	2	0.5	2025-01-28 20:57:02	2025-01-28 20:57:02	\N
87	13	3	x	2025-01-28 20:57:02	2025-01-28 20:57:02	\N
88	13	4	2	2025-01-28 20:57:02	2025-01-28 20:57:02	\N
89	13	5	0	2025-01-28 20:57:02	2025-01-28 20:57:02	\N
90	13	6	%	2025-01-28 20:57:02	2025-01-28 20:57:02	\N
91	13	7	Concentration	2025-01-28 20:57:02	2025-01-28 20:57:02	\N
92	14	1	20	2025-01-28 20:58:27	2025-01-28 20:58:27	\N
93	14	2	10	2025-01-28 20:58:27	2025-01-28 20:58:27	\N
94	14	3	x	2025-01-28 20:58:27	2025-01-28 20:58:27	\N
95	14	4	60	2025-01-28 20:58:27	2025-01-28 20:58:27	\N
96	14	5	0	2025-01-28 20:58:27	2025-01-28 20:58:27	\N
97	14	6	Minutes	2025-01-28 20:58:27	2025-01-28 20:58:27	\N
98	14	7	Time	2025-01-28 20:58:27	2025-01-28 20:58:27	\N
99	15	1	4	2025-01-28 20:59:40	2025-01-28 20:59:40	\N
100	15	2	3	2025-01-28 20:59:40	2025-01-28 20:59:40	\N
101	15	3	x	2025-01-28 20:59:40	2025-01-28 20:59:40	\N
102	15	4	5	2025-01-28 20:59:40	2025-01-28 20:59:40	\N
103	15	5	1	2025-01-28 20:59:40	2025-01-28 20:59:40	\N
104	15	6	Hrs	2025-01-28 20:59:40	2025-01-28 20:59:40	\N
105	15	7	Time	2025-01-28 20:59:40	2025-01-28 20:59:40	\N
106	16	1	100	2025-01-28 21:00:52	2025-01-28 21:00:52	\N
107	16	2	80	2025-01-28 21:00:52	2025-01-28 21:00:52	\N
108	16	3	x	2025-01-28 21:00:52	2025-01-28 21:00:52	\N
109	16	4	120	2025-01-28 21:00:52	2025-01-28 21:00:52	\N
110	16	5	0	2025-01-28 21:00:52	2025-01-28 21:00:52	\N
111	16	6	Qnty	2025-01-28 21:00:52	2025-01-28 21:00:52	\N
112	16	7	Count	2025-01-28 21:00:52	2025-01-28 21:00:52	\N
113	17	1	120	2025-01-28 21:02:46	2025-01-28 21:02:46	\N
114	17	2	80	2025-01-28 21:02:46	2025-01-28 21:02:46	\N
115	17	3	x	2025-01-28 21:02:46	2025-01-28 21:02:46	\N
116	17	4	150	2025-01-28 21:02:46	2025-01-28 21:02:46	\N
117	17	5	0	2025-01-28 21:02:46	2025-01-28 21:02:46	\N
118	17	6	Heat/Day	2025-01-28 21:02:46	2025-01-28 21:02:46	\N
119	17	7	Count	2025-01-28 21:02:46	2025-01-28 21:02:46	\N
120	18	1	1650	2025-01-28 21:04:06	2025-01-28 21:04:06	\N
121	18	2	1550	2025-01-28 21:04:06	2025-01-28 21:04:06	\N
122	18	3	x	2025-01-28 21:04:06	2025-01-28 21:04:06	\N
123	18	4	1700	2025-01-28 21:04:06	2025-01-28 21:04:06	\N
124	18	5	0	2025-01-28 21:04:06	2025-01-28 21:04:06	\N
125	18	6	degC	2025-01-28 21:04:06	2025-01-28 21:04:06	\N
126	18	7	Temperature	2025-01-28 21:04:06	2025-01-28 21:04:06	\N
\.


--
-- TOC entry 4783 (class 0 OID 39813)
-- Dependencies: 320
-- Data for Name: variable_attributes; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.variable_attributes (variable_attribute_id, field_name, display_name, field_type, field_values, field_length, is_required, user_id, created_at, updated_at, deleted_at, list_parameter_id) FROM stdin;
1	var_ucl	Upper Operation Limit	Number	\N	15	f	1	2025-01-28 19:17:30	2025-01-28 19:17:30	\N	\N
2	var_lcl	Lower Operation Limit	Number	\N	10	f	1	2025-01-28 19:18:53	2025-01-28 19:18:53	\N	\N
3	var_sensor_loc	sensor location	Text	\N	15	f	1	2025-01-28 19:19:36	2025-01-28 19:19:36	\N	\N
4	var_sensor_max	sensor max	Number	\N	20	t	1	2025-01-28 19:20:19	2025-01-28 19:20:19	\N	\N
5	var_sensor_min	Sensor Min	Number	\N	15	f	1	2025-01-28 19:20:51	2025-01-28 19:20:51	\N	\N
6	var_uom	UoM	List	\N	15	t	1	2025-01-28 19:21:39	2025-01-28 19:21:39	\N	4
7	var_sensor_type	Sensor Type	Dropdown	Temperature,Pressure,Flow,Concentration,Count,Level,Time,Other	20	t	1	2025-01-28 19:22:19	2025-01-28 19:22:19	\N	\N
\.


--
-- TOC entry 4777 (class 0 OID 39774)
-- Dependencies: 314
-- Data for Name: variable_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.variable_types (variable_type_id, variable_type_code, variable_type_name, created_at, updated_at, deleted_at) FROM stdin;
1	OP	Analog Output from DCS/PLC	2025-01-28 18:47:51	2025-01-28 18:47:51	\N
2	KPI	Key Production Indicators	2025-01-28 18:48:00	2025-01-28 18:48:00	\N
3	MAN	Manually Entered Value	2025-01-28 18:48:12	2025-01-28 18:48:12	\N
4	QC	QC (Entered in LAB/Sensor)	2025-01-28 18:48:24	2025-01-28 18:48:24	\N
5	SP	Set Point (DCS/PLC setting)	2025-01-28 18:48:36	2025-01-28 18:48:36	\N
6	PV	Process Value (sensor)	2025-01-28 18:48:51	2025-01-28 18:48:51	\N
\.


--
-- TOC entry 4799 (class 0 OID 39943)
-- Dependencies: 336
-- Data for Name: variables; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.variables (variable_id, variable_type_id, variable_code, variable_name, created_at, updated_at, deleted_at) FROM stdin;
1	6	CSTR_CASTING_TEMP	Casting Temperature	2025-01-28 20:35:23	2025-01-28 20:35:23	\N
2	6	LDL_POURING_TEMP	Pouring Temperature	2025-01-28 20:37:01	2025-01-28 20:37:01	\N
3	6	LDL_DISSOLVED_O2	Dissolved Oxygen	2025-01-28 20:39:49	2025-01-28 20:39:49	\N
4	6	LDL_CIRCULATN_TEMP	Circulation Temperature	2025-01-28 20:41:24	2025-01-28 20:41:24	\N
5	3	LDL_HEATING_TIME_HALF	Ladle heating time (half)	2025-01-28 20:43:50	2025-01-28 20:43:50	\N
6	3	LDL_HEATING_TIME_FULL	Ladle Heating time (Full)	2025-01-28 20:45:34	2025-01-28 20:45:34	\N
7	6	LDL_LIQMETL_QNTY	Liquid Metal Weight	2025-01-28 20:47:44	2025-01-28 20:47:44	\N
8	6	LDL_CONSUMPTN_Ca	Calcium Addition Consumption	2025-01-28 20:49:26	2025-01-28 20:49:26	\N
9	6	LDL_CONSUMPTN_LIME	Lime Consumption	2025-01-28 20:50:45	2025-01-28 20:50:45	\N
10	6	LDL_CONSUMPTN_AL	Aluminium consumption	2025-01-28 20:52:14	2025-01-28 20:52:14	\N
11	6	LDL_LF_SULPHR	LF in Sulphur	2025-01-28 20:53:45	2025-01-28 20:53:45	\N
12	6	LDL_LF_TEMP	LF in Temperature	2025-01-28 20:55:11	2025-01-28 20:55:11	\N
13	6	LDL_TAPPD_O2	Tapped Oxygen	2025-01-28 20:57:02	2025-01-28 20:57:02	\N
14	6	LDL_HOLDING_TIME	Holding Time (Minutes)	2025-01-28 20:58:27	2025-01-28 20:58:27	\N
15	6	LDL_PROCESS_TIME	Process Time	2025-01-28 20:59:40	2025-01-28 20:59:40	\N
16	3	LDL_BILLET_HEATS	Number of Billet Heats	2025-01-28 21:00:52	2025-01-28 21:00:52	\N
17	3	LDL_LIFE	Number of Heats in Campaign	2025-01-28 21:02:46	2025-01-28 21:02:46	\N
18	6	LDL_TAPPING_TEMP	Tapping Temperature	2025-01-28 21:04:06	2025-01-28 21:04:06	\N
\.


--
-- TOC entry 4987 (class 0 OID 0)
-- Dependencies: 259
-- Name: abilities_ability_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.abilities_ability_id_seq', 108, true);


--
-- TOC entry 4988 (class 0 OID 0)
-- Dependencies: 311
-- Name: accessory_types_accessory_type_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.accessory_types_accessory_type_id_seq', 4, true);


--
-- TOC entry 4989 (class 0 OID 0)
-- Dependencies: 375
-- Name: activity_attribute_types_activity_attribute_type_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.activity_attribute_types_activity_attribute_type_id_seq', 1, false);


--
-- TOC entry 4990 (class 0 OID 0)
-- Dependencies: 379
-- Name: activity_attribute_values_activity_attribute_value_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.activity_attribute_values_activity_attribute_value_id_seq', 1, false);


--
-- TOC entry 4991 (class 0 OID 0)
-- Dependencies: 373
-- Name: activity_attributes_activity_attribute_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.activity_attributes_activity_attribute_id_seq', 1, false);


--
-- TOC entry 4992 (class 0 OID 0)
-- Dependencies: 226
-- Name: areas_area_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.areas_area_id_seq', 6, true);


--
-- TOC entry 4993 (class 0 OID 0)
-- Dependencies: 367
-- Name: asset_accessories_asset_accessory_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.asset_accessories_asset_accessory_id_seq', 1, false);


--
-- TOC entry 4994 (class 0 OID 0)
-- Dependencies: 293
-- Name: asset_attribute_types_asset_attribute_type_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.asset_attribute_types_asset_attribute_type_id_seq', 26, true);


--
-- TOC entry 4995 (class 0 OID 0)
-- Dependencies: 295
-- Name: asset_attribute_values_asset_attribute_value_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.asset_attribute_values_asset_attribute_value_id_seq', 58, true);


--
-- TOC entry 4996 (class 0 OID 0)
-- Dependencies: 291
-- Name: asset_attributes_asset_attribute_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.asset_attributes_asset_attribute_id_seq', 10, true);


--
-- TOC entry 4997 (class 0 OID 0)
-- Dependencies: 273
-- Name: asset_checks_asset_check_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.asset_checks_asset_check_id_seq', 31, true);


--
-- TOC entry 4998 (class 0 OID 0)
-- Dependencies: 387
-- Name: asset_data_source_values_asset_data_source_value_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.asset_data_source_values_asset_data_source_value_id_seq', 24, true);


--
-- TOC entry 4999 (class 0 OID 0)
-- Dependencies: 365
-- Name: asset_data_sources_asset_data_source_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.asset_data_sources_asset_data_source_id_seq', 6, true);


--
-- TOC entry 5000 (class 0 OID 0)
-- Dependencies: 377
-- Name: asset_departments_asset_department_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.asset_departments_asset_department_id_seq', 21, true);


--
-- TOC entry 5001 (class 0 OID 0)
-- Dependencies: 383
-- Name: asset_service_values_asset_service_value_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.asset_service_values_asset_service_value_id_seq', 40, true);


--
-- TOC entry 5002 (class 0 OID 0)
-- Dependencies: 303
-- Name: asset_services_asset_service_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.asset_services_asset_service_id_seq', 10, true);


--
-- TOC entry 5003 (class 0 OID 0)
-- Dependencies: 381
-- Name: asset_spare_values_asset_spare_value_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.asset_spare_values_asset_spare_value_id_seq', 88, true);


--
-- TOC entry 5004 (class 0 OID 0)
-- Dependencies: 271
-- Name: asset_spares_asset_spare_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.asset_spares_asset_spare_id_seq', 22, true);


--
-- TOC entry 5005 (class 0 OID 0)
-- Dependencies: 415
-- Name: asset_template_accessories_asset_template_accessory_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.asset_template_accessories_asset_template_accessory_id_seq', 1, false);


--
-- TOC entry 5006 (class 0 OID 0)
-- Dependencies: 401
-- Name: asset_template_checks_asset_template_check_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.asset_template_checks_asset_template_check_id_seq', 1, false);


--
-- TOC entry 5007 (class 0 OID 0)
-- Dependencies: 411
-- Name: asset_template_datasources_asset_template_datasource_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.asset_template_datasources_asset_template_datasource_id_seq', 1, false);


--
-- TOC entry 5008 (class 0 OID 0)
-- Dependencies: 403
-- Name: asset_template_services_asset_template_service_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.asset_template_services_asset_template_service_id_seq', 1, false);


--
-- TOC entry 5009 (class 0 OID 0)
-- Dependencies: 397
-- Name: asset_template_spares_asset_template_spare_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.asset_template_spares_asset_template_spare_id_seq', 1, false);


--
-- TOC entry 5010 (class 0 OID 0)
-- Dependencies: 407
-- Name: asset_template_variables_asset_template_variable_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.asset_template_variables_asset_template_variable_id_seq', 1, false);


--
-- TOC entry 5011 (class 0 OID 0)
-- Dependencies: 389
-- Name: asset_templates_asset_template_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.asset_templates_asset_template_id_seq', 1, true);


--
-- TOC entry 5012 (class 0 OID 0)
-- Dependencies: 247
-- Name: asset_type_asset_type_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.asset_type_asset_type_id_seq', 50, true);


--
-- TOC entry 5013 (class 0 OID 0)
-- Dependencies: 385
-- Name: asset_variable_values_asset_variable_value_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.asset_variable_values_asset_variable_value_id_seq', 77, true);


--
-- TOC entry 5014 (class 0 OID 0)
-- Dependencies: 363
-- Name: asset_variables_asset_variable_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.asset_variables_asset_variable_id_seq', 11, true);


--
-- TOC entry 5015 (class 0 OID 0)
-- Dependencies: 361
-- Name: asset_zones_asset_zone_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.asset_zones_asset_zone_id_seq', 20, true);


--
-- TOC entry 5016 (class 0 OID 0)
-- Dependencies: 269
-- Name: assets_asset_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.assets_asset_id_seq', 8, true);


--
-- TOC entry 5017 (class 0 OID 0)
-- Dependencies: 333
-- Name: break_down_attribute_types_break_down_attribute_type_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.break_down_attribute_types_break_down_attribute_type_id_seq', 20, true);


--
-- TOC entry 5018 (class 0 OID 0)
-- Dependencies: 351
-- Name: break_down_attribute_values_break_down_attribute_value_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.break_down_attribute_values_break_down_attribute_value_id_seq', 1, false);


--
-- TOC entry 5019 (class 0 OID 0)
-- Dependencies: 323
-- Name: break_down_attributes_break_down_attribute_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.break_down_attributes_break_down_attribute_id_seq', 5, true);


--
-- TOC entry 5020 (class 0 OID 0)
-- Dependencies: 343
-- Name: break_down_lists_break_down_list_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.break_down_lists_break_down_list_id_seq', 1, false);


--
-- TOC entry 5021 (class 0 OID 0)
-- Dependencies: 307
-- Name: break_down_types_break_down_type_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.break_down_types_break_down_type_id_seq', 5, true);


--
-- TOC entry 5022 (class 0 OID 0)
-- Dependencies: 359
-- Name: campaign_results_campaign_result_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.campaign_results_campaign_result_id_seq', 1, false);


--
-- TOC entry 5023 (class 0 OID 0)
-- Dependencies: 357
-- Name: campaigns_campaign_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.campaigns_campaign_id_seq', 1, false);


--
-- TOC entry 5024 (class 0 OID 0)
-- Dependencies: 297
-- Name: check_asset_types_check_asset_type_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.check_asset_types_check_asset_type_id_seq', 35, true);


--
-- TOC entry 5025 (class 0 OID 0)
-- Dependencies: 253
-- Name: checks_check_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.checks_check_id_seq', 33, true);


--
-- TOC entry 5026 (class 0 OID 0)
-- Dependencies: 289
-- Name: consents_consent_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.consents_consent_id_seq', 1, false);


--
-- TOC entry 5027 (class 0 OID 0)
-- Dependencies: 341
-- Name: data_source_asset_types_data_source_asset_type_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.data_source_asset_types_data_source_asset_type_id_seq', 6, true);


--
-- TOC entry 5028 (class 0 OID 0)
-- Dependencies: 327
-- Name: data_source_attribute_types_data_source_attribute_type_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.data_source_attribute_types_data_source_attribute_type_id_seq', 28, true);


--
-- TOC entry 5029 (class 0 OID 0)
-- Dependencies: 353
-- Name: data_source_attribute_values_data_source_attribute_value_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.data_source_attribute_values_data_source_attribute_value_id_seq', 8, true);


--
-- TOC entry 5030 (class 0 OID 0)
-- Dependencies: 317
-- Name: data_source_attributes_data_source_attribute_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.data_source_attributes_data_source_attribute_id_seq', 4, true);


--
-- TOC entry 5031 (class 0 OID 0)
-- Dependencies: 305
-- Name: data_source_types_data_source_type_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.data_source_types_data_source_type_id_seq', 7, true);


--
-- TOC entry 5032 (class 0 OID 0)
-- Dependencies: 339
-- Name: data_sources_data_source_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.data_sources_data_source_id_seq', 2, true);


--
-- TOC entry 5033 (class 0 OID 0)
-- Dependencies: 241
-- Name: departments_department_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.departments_department_id_seq', 9, true);


--
-- TOC entry 5034 (class 0 OID 0)
-- Dependencies: 417
-- Name: downloaded_reports_download_report_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.downloaded_reports_download_report_id_seq', 1, false);


--
-- TOC entry 5035 (class 0 OID 0)
-- Dependencies: 263
-- Name: equipment_equipment_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.equipment_equipment_id_seq', 1, false);


--
-- TOC entry 5036 (class 0 OID 0)
-- Dependencies: 245
-- Name: equipment_types_equipment_type_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.equipment_types_equipment_type_id_seq', 1, false);


--
-- TOC entry 5037 (class 0 OID 0)
-- Dependencies: 224
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- TOC entry 5038 (class 0 OID 0)
-- Dependencies: 239
-- Name: frequencies_frequency_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.frequencies_frequency_id_seq', 1, false);


--
-- TOC entry 5039 (class 0 OID 0)
-- Dependencies: 309
-- Name: functionals_functional_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.functionals_functional_id_seq', 3, true);


--
-- TOC entry 5040 (class 0 OID 0)
-- Dependencies: 221
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- TOC entry 5041 (class 0 OID 0)
-- Dependencies: 355
-- Name: list_parameters_list_parameter_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.list_parameters_list_parameter_id_seq', 4, true);


--
-- TOC entry 5042 (class 0 OID 0)
-- Dependencies: 217
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.migrations_id_seq', 144, true);


--
-- TOC entry 5043 (class 0 OID 0)
-- Dependencies: 257
-- Name: modules_module_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.modules_module_id_seq', 29, true);


--
-- TOC entry 5044 (class 0 OID 0)
-- Dependencies: 237
-- Name: otps_otp_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.otps_otp_id_seq', 1, false);


--
-- TOC entry 5045 (class 0 OID 0)
-- Dependencies: 234
-- Name: password_reset_tokens_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.password_reset_tokens_id_seq', 1, false);


--
-- TOC entry 5046 (class 0 OID 0)
-- Dependencies: 255
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.personal_access_tokens_id_seq', 2, true);


--
-- TOC entry 5047 (class 0 OID 0)
-- Dependencies: 228
-- Name: plants_plant_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.plants_plant_id_seq', 25, true);


--
-- TOC entry 5048 (class 0 OID 0)
-- Dependencies: 275
-- Name: reasons_reason_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.reasons_reason_id_seq', 5, true);


--
-- TOC entry 5049 (class 0 OID 0)
-- Dependencies: 261
-- Name: role_abilities_role_ability_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.role_abilities_role_ability_id_seq', 108, true);


--
-- TOC entry 5050 (class 0 OID 0)
-- Dependencies: 230
-- Name: roles_role_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.roles_role_id_seq', 5, true);


--
-- TOC entry 5051 (class 0 OID 0)
-- Dependencies: 243
-- Name: sections_section_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.sections_section_id_seq', 16, true);


--
-- TOC entry 5052 (class 0 OID 0)
-- Dependencies: 301
-- Name: service_asset_types_service_asset_type_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.service_asset_types_service_asset_type_id_seq', 4, true);


--
-- TOC entry 5053 (class 0 OID 0)
-- Dependencies: 331
-- Name: service_attribute_types_service_attribute_type_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.service_attribute_types_service_attribute_type_id_seq', 24, true);


--
-- TOC entry 5054 (class 0 OID 0)
-- Dependencies: 347
-- Name: service_attribute_values_service_attribute_value_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.service_attribute_values_service_attribute_value_id_seq', 12, true);


--
-- TOC entry 5055 (class 0 OID 0)
-- Dependencies: 321
-- Name: service_attributes_service_attribute_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.service_attributes_service_attribute_id_seq', 4, true);


--
-- TOC entry 5056 (class 0 OID 0)
-- Dependencies: 251
-- Name: service_type_service_type_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.service_type_service_type_id_seq', 8, true);


--
-- TOC entry 5057 (class 0 OID 0)
-- Dependencies: 267
-- Name: services_service_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.services_service_id_seq', 3, true);


--
-- TOC entry 5058 (class 0 OID 0)
-- Dependencies: 299
-- Name: spare_asset_types_spare_asset_type_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.spare_asset_types_spare_asset_type_id_seq', 38, true);


--
-- TOC entry 5059 (class 0 OID 0)
-- Dependencies: 325
-- Name: spare_attribute_types_spare_attribute_type_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.spare_attribute_types_spare_attribute_type_id_seq', 21, true);


--
-- TOC entry 5060 (class 0 OID 0)
-- Dependencies: 345
-- Name: spare_attribute_values_spare_attribute_value_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.spare_attribute_values_spare_attribute_value_id_seq', 104, true);


--
-- TOC entry 5061 (class 0 OID 0)
-- Dependencies: 315
-- Name: spare_attributes_spare_attribute_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.spare_attributes_spare_attribute_id_seq', 4, true);


--
-- TOC entry 5062 (class 0 OID 0)
-- Dependencies: 249
-- Name: spare_types_spare_type_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.spare_types_spare_type_id_seq', 20, true);


--
-- TOC entry 5063 (class 0 OID 0)
-- Dependencies: 265
-- Name: spares_spare_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.spares_spare_id_seq', 26, true);


--
-- TOC entry 5064 (class 0 OID 0)
-- Dependencies: 395
-- Name: template_attribute_values_template_attribute_value_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.template_attribute_values_template_attribute_value_id_seq', 9, true);


--
-- TOC entry 5065 (class 0 OID 0)
-- Dependencies: 413
-- Name: template_datasource_values_template_datasource_value_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.template_datasource_values_template_datasource_value_id_seq', 1, false);


--
-- TOC entry 5066 (class 0 OID 0)
-- Dependencies: 393
-- Name: template_departments_template_department_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.template_departments_template_department_id_seq', 1, true);


--
-- TOC entry 5067 (class 0 OID 0)
-- Dependencies: 405
-- Name: template_service_values_template_service_value_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.template_service_values_template_service_value_id_seq', 1, false);


--
-- TOC entry 5068 (class 0 OID 0)
-- Dependencies: 399
-- Name: template_spare_values_template_spare_value_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.template_spare_values_template_spare_value_id_seq', 1, false);


--
-- TOC entry 5069 (class 0 OID 0)
-- Dependencies: 409
-- Name: template_variable_values_template_variable_value_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.template_variable_values_template_variable_value_id_seq', 1, false);


--
-- TOC entry 5070 (class 0 OID 0)
-- Dependencies: 391
-- Name: template_zones_template_zone_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.template_zones_template_zone_id_seq', 3, true);


--
-- TOC entry 5071 (class 0 OID 0)
-- Dependencies: 277
-- Name: user_activities_user_activity_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.user_activities_user_activity_id_seq', 1, false);


--
-- TOC entry 5072 (class 0 OID 0)
-- Dependencies: 285
-- Name: user_asset_checks_user_asset_check_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.user_asset_checks_user_asset_check_id_seq', 1, false);


--
-- TOC entry 5073 (class 0 OID 0)
-- Dependencies: 371
-- Name: user_asset_variables_user_asset_variable_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.user_asset_variables_user_asset_variable_id_seq', 1, false);


--
-- TOC entry 5074 (class 0 OID 0)
-- Dependencies: 287
-- Name: user_check_attachments_user_check_attachment_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.user_check_attachments_user_check_attachment_id_seq', 1, false);


--
-- TOC entry 5075 (class 0 OID 0)
-- Dependencies: 283
-- Name: user_checks_user_check_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.user_checks_user_check_id_seq', 1, false);


--
-- TOC entry 5076 (class 0 OID 0)
-- Dependencies: 279
-- Name: user_services_user_service_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.user_services_user_service_id_seq', 1, false);


--
-- TOC entry 5077 (class 0 OID 0)
-- Dependencies: 281
-- Name: user_spares_user_spare_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.user_spares_user_spare_id_seq', 1, false);


--
-- TOC entry 5078 (class 0 OID 0)
-- Dependencies: 369
-- Name: user_variables_user_variable_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.user_variables_user_variable_id_seq', 1, false);


--
-- TOC entry 5079 (class 0 OID 0)
-- Dependencies: 232
-- Name: users_user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_user_id_seq', 3, true);


--
-- TOC entry 5080 (class 0 OID 0)
-- Dependencies: 337
-- Name: variable_asset_types_variable_asset_type_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.variable_asset_types_variable_asset_type_id_seq', 46, true);


--
-- TOC entry 5081 (class 0 OID 0)
-- Dependencies: 329
-- Name: variable_attribute_types_variable_attribute_type_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.variable_attribute_types_variable_attribute_type_id_seq', 32, true);


--
-- TOC entry 5082 (class 0 OID 0)
-- Dependencies: 349
-- Name: variable_attribute_values_variable_attribute_value_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.variable_attribute_values_variable_attribute_value_id_seq', 126, true);


--
-- TOC entry 5083 (class 0 OID 0)
-- Dependencies: 319
-- Name: variable_attributes_variable_attribute_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.variable_attributes_variable_attribute_id_seq', 7, true);


--
-- TOC entry 5084 (class 0 OID 0)
-- Dependencies: 313
-- Name: variable_types_variable_type_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.variable_types_variable_type_id_seq', 6, true);


--
-- TOC entry 5085 (class 0 OID 0)
-- Dependencies: 335
-- Name: variables_variable_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.variables_variable_id_seq', 18, true);


--
-- TOC entry 4122 (class 2606 OID 39310)
-- Name: abilities abilities_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.abilities
    ADD CONSTRAINT abilities_pkey PRIMARY KEY (ability_id);


--
-- TOC entry 4174 (class 2606 OID 39772)
-- Name: accessory_types accessory_types_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.accessory_types
    ADD CONSTRAINT accessory_types_pkey PRIMARY KEY (accessory_type_id);


--
-- TOC entry 4258 (class 2606 OID 40468)
-- Name: activity_attribute_types activity_attribute_types_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.activity_attribute_types
    ADD CONSTRAINT activity_attribute_types_pkey PRIMARY KEY (activity_attribute_type_id);


--
-- TOC entry 4262 (class 2606 OID 40502)
-- Name: activity_attribute_values activity_attribute_values_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.activity_attribute_values
    ADD CONSTRAINT activity_attribute_values_pkey PRIMARY KEY (activity_attribute_value_id);


--
-- TOC entry 4256 (class 2606 OID 40451)
-- Name: activity_attributes activity_attributes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.activity_attributes
    ADD CONSTRAINT activity_attributes_pkey PRIMARY KEY (activity_attribute_id);


--
-- TOC entry 4083 (class 2606 OID 39147)
-- Name: areas areas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.areas
    ADD CONSTRAINT areas_pkey PRIMARY KEY (area_id);


--
-- TOC entry 4249 (class 2606 OID 40324)
-- Name: asset_accessories asset_accessories_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_accessories
    ADD CONSTRAINT asset_accessories_pkey PRIMARY KEY (asset_accessory_id);


--
-- TOC entry 4156 (class 2606 OID 39613)
-- Name: asset_attribute_types asset_attribute_types_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_attribute_types
    ADD CONSTRAINT asset_attribute_types_pkey PRIMARY KEY (asset_attribute_type_id);


--
-- TOC entry 4158 (class 2606 OID 39630)
-- Name: asset_attribute_values asset_attribute_values_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_attribute_values
    ADD CONSTRAINT asset_attribute_values_pkey PRIMARY KEY (asset_attribute_value_id);


--
-- TOC entry 4154 (class 2606 OID 39601)
-- Name: asset_attributes asset_attributes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_attributes
    ADD CONSTRAINT asset_attributes_pkey PRIMARY KEY (asset_attribute_id);


--
-- TOC entry 4136 (class 2606 OID 39416)
-- Name: asset_checks asset_checks_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_checks
    ADD CONSTRAINT asset_checks_pkey PRIMARY KEY (asset_check_id);


--
-- TOC entry 4270 (class 2606 OID 40620)
-- Name: asset_data_source_values asset_data_source_values_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_data_source_values
    ADD CONSTRAINT asset_data_source_values_pkey PRIMARY KEY (asset_data_source_value_id);


--
-- TOC entry 4240 (class 2606 OID 40281)
-- Name: asset_data_sources asset_data_sources_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_data_sources
    ADD CONSTRAINT asset_data_sources_pkey PRIMARY KEY (asset_data_source_id);


--
-- TOC entry 4260 (class 2606 OID 40485)
-- Name: asset_departments asset_departments_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_departments
    ADD CONSTRAINT asset_departments_pkey PRIMARY KEY (asset_department_id);


--
-- TOC entry 4266 (class 2606 OID 40556)
-- Name: asset_service_values asset_service_values_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_service_values
    ADD CONSTRAINT asset_service_values_pkey PRIMARY KEY (asset_service_value_id);


--
-- TOC entry 4166 (class 2606 OID 39698)
-- Name: asset_services asset_services_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_services
    ADD CONSTRAINT asset_services_pkey PRIMARY KEY (asset_service_id);


--
-- TOC entry 4264 (class 2606 OID 40524)
-- Name: asset_spare_values asset_spare_values_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_spare_values
    ADD CONSTRAINT asset_spare_values_pkey PRIMARY KEY (asset_spare_value_id);


--
-- TOC entry 4134 (class 2606 OID 39394)
-- Name: asset_spares asset_spares_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_spares
    ADD CONSTRAINT asset_spares_pkey PRIMARY KEY (asset_spare_id);


--
-- TOC entry 4303 (class 2606 OID 41046)
-- Name: asset_template_accessories asset_template_accessories_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_accessories
    ADD CONSTRAINT asset_template_accessories_pkey PRIMARY KEY (asset_template_accessory_id);


--
-- TOC entry 4285 (class 2606 OID 40807)
-- Name: asset_template_checks asset_template_checks_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_checks
    ADD CONSTRAINT asset_template_checks_pkey PRIMARY KEY (asset_template_check_id);


--
-- TOC entry 4295 (class 2606 OID 40977)
-- Name: asset_template_datasources asset_template_datasources_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_datasources
    ADD CONSTRAINT asset_template_datasources_pkey PRIMARY KEY (asset_template_datasource_id);


--
-- TOC entry 4287 (class 2606 OID 40839)
-- Name: asset_template_services asset_template_services_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_services
    ADD CONSTRAINT asset_template_services_pkey PRIMARY KEY (asset_template_service_id);


--
-- TOC entry 4281 (class 2606 OID 40738)
-- Name: asset_template_spares asset_template_spares_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_spares
    ADD CONSTRAINT asset_template_spares_pkey PRIMARY KEY (asset_template_spare_id);


--
-- TOC entry 4291 (class 2606 OID 40908)
-- Name: asset_template_variables asset_template_variables_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_variables
    ADD CONSTRAINT asset_template_variables_pkey PRIMARY KEY (asset_template_variable_id);


--
-- TOC entry 4272 (class 2606 OID 40659)
-- Name: asset_templates asset_templates_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_templates
    ADD CONSTRAINT asset_templates_pkey PRIMARY KEY (asset_template_id);


--
-- TOC entry 4107 (class 2606 OID 39256)
-- Name: asset_type asset_type_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_type
    ADD CONSTRAINT asset_type_pkey PRIMARY KEY (asset_type_id);


--
-- TOC entry 4268 (class 2606 OID 40588)
-- Name: asset_variable_values asset_variable_values_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_variable_values
    ADD CONSTRAINT asset_variable_values_pkey PRIMARY KEY (asset_variable_value_id);


--
-- TOC entry 4230 (class 2606 OID 40238)
-- Name: asset_variables asset_variables_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_variables
    ADD CONSTRAINT asset_variables_pkey PRIMARY KEY (asset_variable_id);


--
-- TOC entry 4225 (class 2606 OID 40185)
-- Name: asset_zones asset_zones_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_zones
    ADD CONSTRAINT asset_zones_pkey PRIMARY KEY (asset_zone_id);


--
-- TOC entry 4132 (class 2606 OID 39377)
-- Name: assets assets_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assets
    ADD CONSTRAINT assets_pkey PRIMARY KEY (asset_id);


--
-- TOC entry 4196 (class 2606 OID 39931)
-- Name: break_down_attribute_types break_down_attribute_types_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.break_down_attribute_types
    ADD CONSTRAINT break_down_attribute_types_pkey PRIMARY KEY (break_down_attribute_type_id);


--
-- TOC entry 4214 (class 2606 OID 40069)
-- Name: break_down_attribute_values break_down_attribute_values_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.break_down_attribute_values
    ADD CONSTRAINT break_down_attribute_values_pkey PRIMARY KEY (break_down_attribute_value_id);


--
-- TOC entry 4186 (class 2606 OID 39851)
-- Name: break_down_attributes break_down_attributes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.break_down_attributes
    ADD CONSTRAINT break_down_attributes_pkey PRIMARY KEY (break_down_attribute_id);


--
-- TOC entry 4206 (class 2606 OID 40006)
-- Name: break_down_lists break_down_lists_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.break_down_lists
    ADD CONSTRAINT break_down_lists_pkey PRIMARY KEY (break_down_list_id);


--
-- TOC entry 4170 (class 2606 OID 39754)
-- Name: break_down_types break_down_types_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.break_down_types
    ADD CONSTRAINT break_down_types_pkey PRIMARY KEY (break_down_type_id);


--
-- TOC entry 4072 (class 2606 OID 39111)
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- TOC entry 4070 (class 2606 OID 39104)
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- TOC entry 4222 (class 2606 OID 40158)
-- Name: campaign_results campaign_results_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.campaign_results
    ADD CONSTRAINT campaign_results_pkey PRIMARY KEY (campaign_result_id);


--
-- TOC entry 4220 (class 2606 OID 40144)
-- Name: campaigns campaigns_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.campaigns
    ADD CONSTRAINT campaigns_pkey PRIMARY KEY (campaign_id);


--
-- TOC entry 4160 (class 2606 OID 39647)
-- Name: check_asset_types check_asset_types_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.check_asset_types
    ADD CONSTRAINT check_asset_types_pkey PRIMARY KEY (check_asset_type_id);


--
-- TOC entry 4113 (class 2606 OID 39280)
-- Name: checks checks_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.checks
    ADD CONSTRAINT checks_pkey PRIMARY KEY (check_id);


--
-- TOC entry 4152 (class 2606 OID 39586)
-- Name: consents consents_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.consents
    ADD CONSTRAINT consents_pkey PRIMARY KEY (consent_id);


--
-- TOC entry 4204 (class 2606 OID 39989)
-- Name: data_source_asset_types data_source_asset_types_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.data_source_asset_types
    ADD CONSTRAINT data_source_asset_types_pkey PRIMARY KEY (data_source_asset_type_id);


--
-- TOC entry 4190 (class 2606 OID 39880)
-- Name: data_source_attribute_types data_source_attribute_types_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.data_source_attribute_types
    ADD CONSTRAINT data_source_attribute_types_pkey PRIMARY KEY (data_source_attribute_type_id);


--
-- TOC entry 4216 (class 2606 OID 40086)
-- Name: data_source_attribute_values data_source_attribute_values_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.data_source_attribute_values
    ADD CONSTRAINT data_source_attribute_values_pkey PRIMARY KEY (data_source_attribute_value_id);


--
-- TOC entry 4180 (class 2606 OID 39806)
-- Name: data_source_attributes data_source_attributes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.data_source_attributes
    ADD CONSTRAINT data_source_attributes_pkey PRIMARY KEY (data_source_attribute_id);


--
-- TOC entry 4168 (class 2606 OID 39745)
-- Name: data_source_types data_source_types_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.data_source_types
    ADD CONSTRAINT data_source_types_pkey PRIMARY KEY (data_source_type_id);


--
-- TOC entry 4202 (class 2606 OID 39977)
-- Name: data_sources data_sources_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.data_sources
    ADD CONSTRAINT data_sources_pkey PRIMARY KEY (data_source_id);


--
-- TOC entry 4101 (class 2606 OID 39235)
-- Name: departments departments_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.departments
    ADD CONSTRAINT departments_pkey PRIMARY KEY (department_id);


--
-- TOC entry 4307 (class 2606 OID 41091)
-- Name: downloaded_reports downloaded_reports_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.downloaded_reports
    ADD CONSTRAINT downloaded_reports_pkey PRIMARY KEY (download_report_id);


--
-- TOC entry 4126 (class 2606 OID 39341)
-- Name: equipment equipment_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.equipment
    ADD CONSTRAINT equipment_pkey PRIMARY KEY (equipment_id);


--
-- TOC entry 4105 (class 2606 OID 39249)
-- Name: equipment_types equipment_types_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.equipment_types
    ADD CONSTRAINT equipment_types_pkey PRIMARY KEY (equipment_type_id);


--
-- TOC entry 4079 (class 2606 OID 39138)
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- TOC entry 4081 (class 2606 OID 39140)
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- TOC entry 4099 (class 2606 OID 39226)
-- Name: frequencies frequencies_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.frequencies
    ADD CONSTRAINT frequencies_pkey PRIMARY KEY (frequency_id);


--
-- TOC entry 4172 (class 2606 OID 39763)
-- Name: functionals functionals_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.functionals
    ADD CONSTRAINT functionals_pkey PRIMARY KEY (functional_id);


--
-- TOC entry 4077 (class 2606 OID 39128)
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- TOC entry 4074 (class 2606 OID 39120)
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- TOC entry 4218 (class 2606 OID 40105)
-- Name: list_parameters list_parameters_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.list_parameters
    ADD CONSTRAINT list_parameters_pkey PRIMARY KEY (list_parameter_id);


--
-- TOC entry 4068 (class 2606 OID 39097)
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- TOC entry 4120 (class 2606 OID 39301)
-- Name: modules modules_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.modules
    ADD CONSTRAINT modules_pkey PRIMARY KEY (module_id);


--
-- TOC entry 4097 (class 2606 OID 39212)
-- Name: otps otps_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.otps
    ADD CONSTRAINT otps_pkey PRIMARY KEY (otp_id);


--
-- TOC entry 4091 (class 2606 OID 39196)
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (id);


--
-- TOC entry 4115 (class 2606 OID 39289)
-- Name: personal_access_tokens personal_access_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_pkey PRIMARY KEY (id);


--
-- TOC entry 4117 (class 2606 OID 39292)
-- Name: personal_access_tokens personal_access_tokens_token_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_token_unique UNIQUE (token);


--
-- TOC entry 4085 (class 2606 OID 39154)
-- Name: plants plants_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.plants
    ADD CONSTRAINT plants_pkey PRIMARY KEY (plant_id);


--
-- TOC entry 4138 (class 2606 OID 39438)
-- Name: reasons reasons_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reasons
    ADD CONSTRAINT reasons_pkey PRIMARY KEY (reason_id);


--
-- TOC entry 4124 (class 2606 OID 39322)
-- Name: role_abilities role_abilities_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.role_abilities
    ADD CONSTRAINT role_abilities_pkey PRIMARY KEY (role_ability_id);


--
-- TOC entry 4087 (class 2606 OID 39168)
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (role_id);


--
-- TOC entry 4103 (class 2606 OID 39242)
-- Name: sections sections_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sections
    ADD CONSTRAINT sections_pkey PRIMARY KEY (section_id);


--
-- TOC entry 4164 (class 2606 OID 39681)
-- Name: service_asset_types service_asset_types_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.service_asset_types
    ADD CONSTRAINT service_asset_types_pkey PRIMARY KEY (service_asset_type_id);


--
-- TOC entry 4194 (class 2606 OID 39914)
-- Name: service_attribute_types service_attribute_types_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.service_attribute_types
    ADD CONSTRAINT service_attribute_types_pkey PRIMARY KEY (service_attribute_type_id);


--
-- TOC entry 4210 (class 2606 OID 40035)
-- Name: service_attribute_values service_attribute_values_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.service_attribute_values
    ADD CONSTRAINT service_attribute_values_pkey PRIMARY KEY (service_attribute_value_id);


--
-- TOC entry 4184 (class 2606 OID 39836)
-- Name: service_attributes service_attributes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.service_attributes
    ADD CONSTRAINT service_attributes_pkey PRIMARY KEY (service_attribute_id);


--
-- TOC entry 4111 (class 2606 OID 39270)
-- Name: service_type service_type_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.service_type
    ADD CONSTRAINT service_type_pkey PRIMARY KEY (service_type_id);


--
-- TOC entry 4130 (class 2606 OID 39370)
-- Name: services services_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.services
    ADD CONSTRAINT services_pkey PRIMARY KEY (service_id);


--
-- TOC entry 4094 (class 2606 OID 39203)
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- TOC entry 4162 (class 2606 OID 39664)
-- Name: spare_asset_types spare_asset_types_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.spare_asset_types
    ADD CONSTRAINT spare_asset_types_pkey PRIMARY KEY (spare_asset_type_id);


--
-- TOC entry 4188 (class 2606 OID 39863)
-- Name: spare_attribute_types spare_attribute_types_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.spare_attribute_types
    ADD CONSTRAINT spare_attribute_types_pkey PRIMARY KEY (spare_attribute_type_id);


--
-- TOC entry 4208 (class 2606 OID 40018)
-- Name: spare_attribute_values spare_attribute_values_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.spare_attribute_values
    ADD CONSTRAINT spare_attribute_values_pkey PRIMARY KEY (spare_attribute_value_id);


--
-- TOC entry 4178 (class 2606 OID 39791)
-- Name: spare_attributes spare_attributes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.spare_attributes
    ADD CONSTRAINT spare_attributes_pkey PRIMARY KEY (spare_attribute_id);


--
-- TOC entry 4109 (class 2606 OID 39263)
-- Name: spare_types spare_types_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.spare_types
    ADD CONSTRAINT spare_types_pkey PRIMARY KEY (spare_type_id);


--
-- TOC entry 4128 (class 2606 OID 39358)
-- Name: spares spares_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.spares
    ADD CONSTRAINT spares_pkey PRIMARY KEY (spare_id);


--
-- TOC entry 4279 (class 2606 OID 40721)
-- Name: template_attribute_values template_attribute_values_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.template_attribute_values
    ADD CONSTRAINT template_attribute_values_pkey PRIMARY KEY (template_attribute_value_id);


--
-- TOC entry 4297 (class 2606 OID 41014)
-- Name: template_datasource_values template_datasource_values_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.template_datasource_values
    ADD CONSTRAINT template_datasource_values_pkey PRIMARY KEY (template_datasource_value_id);


--
-- TOC entry 4277 (class 2606 OID 40704)
-- Name: template_departments template_departments_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.template_departments
    ADD CONSTRAINT template_departments_pkey PRIMARY KEY (template_department_id);


--
-- TOC entry 4289 (class 2606 OID 40876)
-- Name: template_service_values template_service_values_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.template_service_values
    ADD CONSTRAINT template_service_values_pkey PRIMARY KEY (template_service_value_id);


--
-- TOC entry 4283 (class 2606 OID 40775)
-- Name: template_spare_values template_spare_values_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.template_spare_values
    ADD CONSTRAINT template_spare_values_pkey PRIMARY KEY (template_spare_value_id);


--
-- TOC entry 4293 (class 2606 OID 40945)
-- Name: template_variable_values template_variable_values_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.template_variable_values
    ADD CONSTRAINT template_variable_values_pkey PRIMARY KEY (template_variable_value_id);


--
-- TOC entry 4275 (class 2606 OID 40691)
-- Name: template_zones template_zones_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.template_zones
    ADD CONSTRAINT template_zones_pkey PRIMARY KEY (template_zone_id);


--
-- TOC entry 4140 (class 2606 OID 39447)
-- Name: user_activities user_activities_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_activities
    ADD CONSTRAINT user_activities_pkey PRIMARY KEY (user_activity_id);


--
-- TOC entry 4148 (class 2606 OID 39547)
-- Name: user_asset_checks user_asset_checks_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_asset_checks
    ADD CONSTRAINT user_asset_checks_pkey PRIMARY KEY (user_asset_check_id);


--
-- TOC entry 4254 (class 2606 OID 40402)
-- Name: user_asset_variables user_asset_variables_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_asset_variables
    ADD CONSTRAINT user_asset_variables_pkey PRIMARY KEY (user_asset_variable_id);


--
-- TOC entry 4150 (class 2606 OID 39569)
-- Name: user_check_attachments user_check_attachments_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_check_attachments
    ADD CONSTRAINT user_check_attachments_pkey PRIMARY KEY (user_check_attachment_id);


--
-- TOC entry 4146 (class 2606 OID 39522)
-- Name: user_checks user_checks_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_checks
    ADD CONSTRAINT user_checks_pkey PRIMARY KEY (user_check_id);


--
-- TOC entry 4142 (class 2606 OID 39476)
-- Name: user_services user_services_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_services
    ADD CONSTRAINT user_services_pkey PRIMARY KEY (user_service_id);


--
-- TOC entry 4144 (class 2606 OID 39503)
-- Name: user_spares user_spares_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_spares
    ADD CONSTRAINT user_spares_pkey PRIMARY KEY (user_spare_id);


--
-- TOC entry 4252 (class 2606 OID 40375)
-- Name: user_variables user_variables_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_variables
    ADD CONSTRAINT user_variables_pkey PRIMARY KEY (user_variable_id);


--
-- TOC entry 4089 (class 2606 OID 39177)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (user_id);


--
-- TOC entry 4200 (class 2606 OID 39960)
-- Name: variable_asset_types variable_asset_types_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.variable_asset_types
    ADD CONSTRAINT variable_asset_types_pkey PRIMARY KEY (variable_asset_type_id);


--
-- TOC entry 4192 (class 2606 OID 39897)
-- Name: variable_attribute_types variable_attribute_types_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.variable_attribute_types
    ADD CONSTRAINT variable_attribute_types_pkey PRIMARY KEY (variable_attribute_type_id);


--
-- TOC entry 4212 (class 2606 OID 40052)
-- Name: variable_attribute_values variable_attribute_values_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.variable_attribute_values
    ADD CONSTRAINT variable_attribute_values_pkey PRIMARY KEY (variable_attribute_value_id);


--
-- TOC entry 4182 (class 2606 OID 39821)
-- Name: variable_attributes variable_attributes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.variable_attributes
    ADD CONSTRAINT variable_attributes_pkey PRIMARY KEY (variable_attribute_id);


--
-- TOC entry 4176 (class 2606 OID 39781)
-- Name: variable_types variable_types_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.variable_types
    ADD CONSTRAINT variable_types_pkey PRIMARY KEY (variable_type_id);


--
-- TOC entry 4198 (class 2606 OID 39948)
-- Name: variables variables_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.variables
    ADD CONSTRAINT variables_pkey PRIMARY KEY (variable_id);


--
-- TOC entry 4242 (class 1259 OID 40355)
-- Name: asset_accessories_accessory_name_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX asset_accessories_accessory_name_index ON public.asset_accessories USING btree (accessory_name);


--
-- TOC entry 4243 (class 1259 OID 40354)
-- Name: asset_accessories_accessory_type_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX asset_accessories_accessory_type_id_index ON public.asset_accessories USING btree (accessory_type_id);


--
-- TOC entry 4244 (class 1259 OID 40350)
-- Name: asset_accessories_area_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX asset_accessories_area_id_index ON public.asset_accessories USING btree (area_id);


--
-- TOC entry 4245 (class 1259 OID 40352)
-- Name: asset_accessories_asset_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX asset_accessories_asset_id_index ON public.asset_accessories USING btree (asset_id);


--
-- TOC entry 4246 (class 1259 OID 40353)
-- Name: asset_accessories_asset_zone_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX asset_accessories_asset_zone_id_index ON public.asset_accessories USING btree (asset_zone_id);


--
-- TOC entry 4247 (class 1259 OID 40356)
-- Name: asset_accessories_attachment_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX asset_accessories_attachment_index ON public.asset_accessories USING btree (attachment);


--
-- TOC entry 4250 (class 1259 OID 40351)
-- Name: asset_accessories_plant_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX asset_accessories_plant_id_index ON public.asset_accessories USING btree (plant_id);


--
-- TOC entry 4234 (class 1259 OID 40312)
-- Name: asset_data_sources_area_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX asset_data_sources_area_id_index ON public.asset_data_sources USING btree (area_id);


--
-- TOC entry 4235 (class 1259 OID 40314)
-- Name: asset_data_sources_asset_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX asset_data_sources_asset_id_index ON public.asset_data_sources USING btree (asset_id);


--
-- TOC entry 4236 (class 1259 OID 40315)
-- Name: asset_data_sources_asset_zone_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX asset_data_sources_asset_zone_id_index ON public.asset_data_sources USING btree (asset_zone_id);


--
-- TOC entry 4237 (class 1259 OID 40317)
-- Name: asset_data_sources_data_source_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX asset_data_sources_data_source_id_index ON public.asset_data_sources USING btree (data_source_id);


--
-- TOC entry 4238 (class 1259 OID 40316)
-- Name: asset_data_sources_data_source_type_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX asset_data_sources_data_source_type_id_index ON public.asset_data_sources USING btree (data_source_type_id);


--
-- TOC entry 4241 (class 1259 OID 40313)
-- Name: asset_data_sources_plant_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX asset_data_sources_plant_id_index ON public.asset_data_sources USING btree (plant_id);


--
-- TOC entry 4298 (class 1259 OID 41077)
-- Name: asset_template_accessories_accessory_name_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX asset_template_accessories_accessory_name_index ON public.asset_template_accessories USING btree (accessory_name);


--
-- TOC entry 4299 (class 1259 OID 41076)
-- Name: asset_template_accessories_accessory_type_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX asset_template_accessories_accessory_type_id_index ON public.asset_template_accessories USING btree (accessory_type_id);


--
-- TOC entry 4300 (class 1259 OID 41072)
-- Name: asset_template_accessories_area_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX asset_template_accessories_area_id_index ON public.asset_template_accessories USING btree (area_id);


--
-- TOC entry 4301 (class 1259 OID 41074)
-- Name: asset_template_accessories_asset_template_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX asset_template_accessories_asset_template_id_index ON public.asset_template_accessories USING btree (asset_template_id);


--
-- TOC entry 4304 (class 1259 OID 41073)
-- Name: asset_template_accessories_plant_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX asset_template_accessories_plant_id_index ON public.asset_template_accessories USING btree (plant_id);


--
-- TOC entry 4305 (class 1259 OID 41075)
-- Name: asset_template_accessories_template_zone_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX asset_template_accessories_template_zone_id_index ON public.asset_template_accessories USING btree (template_zone_id);


--
-- TOC entry 4226 (class 1259 OID 40269)
-- Name: asset_variables_area_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX asset_variables_area_id_index ON public.asset_variables USING btree (area_id);


--
-- TOC entry 4227 (class 1259 OID 40271)
-- Name: asset_variables_asset_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX asset_variables_asset_id_index ON public.asset_variables USING btree (asset_id);


--
-- TOC entry 4228 (class 1259 OID 40272)
-- Name: asset_variables_asset_zone_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX asset_variables_asset_zone_id_index ON public.asset_variables USING btree (asset_zone_id);


--
-- TOC entry 4231 (class 1259 OID 40270)
-- Name: asset_variables_plant_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX asset_variables_plant_id_index ON public.asset_variables USING btree (plant_id);


--
-- TOC entry 4232 (class 1259 OID 40274)
-- Name: asset_variables_variable_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX asset_variables_variable_id_index ON public.asset_variables USING btree (variable_id);


--
-- TOC entry 4233 (class 1259 OID 40273)
-- Name: asset_variables_variable_type_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX asset_variables_variable_type_id_index ON public.asset_variables USING btree (variable_type_id);


--
-- TOC entry 4223 (class 1259 OID 40191)
-- Name: asset_zones_asset_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX asset_zones_asset_id_index ON public.asset_zones USING btree (asset_id);


--
-- TOC entry 4075 (class 1259 OID 39121)
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- TOC entry 4118 (class 1259 OID 39290)
-- Name: personal_access_tokens_tokenable_type_tokenable_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON public.personal_access_tokens USING btree (tokenable_type, tokenable_id);


--
-- TOC entry 4092 (class 1259 OID 39205)
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- TOC entry 4095 (class 1259 OID 39204)
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- TOC entry 4273 (class 1259 OID 40697)
-- Name: template_zones_asset_template_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX template_zones_asset_template_id_index ON public.template_zones USING btree (asset_template_id);


--
-- TOC entry 4314 (class 2606 OID 39311)
-- Name: abilities abilities_module_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.abilities
    ADD CONSTRAINT abilities_module_id_foreign FOREIGN KEY (module_id) REFERENCES public.modules(module_id);


--
-- TOC entry 4444 (class 2606 OID 40469)
-- Name: activity_attribute_types activity_attribute_types_activity_attribute_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.activity_attribute_types
    ADD CONSTRAINT activity_attribute_types_activity_attribute_id_foreign FOREIGN KEY (activity_attribute_id) REFERENCES public.activity_attributes(activity_attribute_id);


--
-- TOC entry 4445 (class 2606 OID 40474)
-- Name: activity_attribute_types activity_attribute_types_reason_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.activity_attribute_types
    ADD CONSTRAINT activity_attribute_types_reason_id_foreign FOREIGN KEY (reason_id) REFERENCES public.reasons(reason_id);


--
-- TOC entry 4448 (class 2606 OID 40508)
-- Name: activity_attribute_values activity_attribute_values_activity_attribute_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.activity_attribute_values
    ADD CONSTRAINT activity_attribute_values_activity_attribute_id_foreign FOREIGN KEY (activity_attribute_id) REFERENCES public.activity_attributes(activity_attribute_id);


--
-- TOC entry 4449 (class 2606 OID 40503)
-- Name: activity_attribute_values activity_attribute_values_user_activity_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.activity_attribute_values
    ADD CONSTRAINT activity_attribute_values_user_activity_id_foreign FOREIGN KEY (user_activity_id) REFERENCES public.user_activities(user_activity_id);


--
-- TOC entry 4442 (class 2606 OID 40457)
-- Name: activity_attributes activity_attributes_list_parameter_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.activity_attributes
    ADD CONSTRAINT activity_attributes_list_parameter_id_foreign FOREIGN KEY (list_parameter_id) REFERENCES public.list_parameters(list_parameter_id);


--
-- TOC entry 4443 (class 2606 OID 40452)
-- Name: activity_attributes activity_attributes_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.activity_attributes
    ADD CONSTRAINT activity_attributes_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(user_id);


--
-- TOC entry 4431 (class 2606 OID 40345)
-- Name: asset_accessories asset_accessories_accessory_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_accessories
    ADD CONSTRAINT asset_accessories_accessory_type_id_foreign FOREIGN KEY (accessory_type_id) REFERENCES public.accessory_types(accessory_type_id);


--
-- TOC entry 4432 (class 2606 OID 40325)
-- Name: asset_accessories asset_accessories_area_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_accessories
    ADD CONSTRAINT asset_accessories_area_id_foreign FOREIGN KEY (area_id) REFERENCES public.areas(area_id);


--
-- TOC entry 4433 (class 2606 OID 40335)
-- Name: asset_accessories asset_accessories_asset_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_accessories
    ADD CONSTRAINT asset_accessories_asset_id_foreign FOREIGN KEY (asset_id) REFERENCES public.assets(asset_id);


--
-- TOC entry 4434 (class 2606 OID 40340)
-- Name: asset_accessories asset_accessories_asset_zone_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_accessories
    ADD CONSTRAINT asset_accessories_asset_zone_id_foreign FOREIGN KEY (asset_zone_id) REFERENCES public.asset_zones(asset_zone_id);


--
-- TOC entry 4435 (class 2606 OID 40330)
-- Name: asset_accessories asset_accessories_plant_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_accessories
    ADD CONSTRAINT asset_accessories_plant_id_foreign FOREIGN KEY (plant_id) REFERENCES public.plants(plant_id);


--
-- TOC entry 4361 (class 2606 OID 39614)
-- Name: asset_attribute_types asset_attribute_types_asset_attribute_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_attribute_types
    ADD CONSTRAINT asset_attribute_types_asset_attribute_id_foreign FOREIGN KEY (asset_attribute_id) REFERENCES public.asset_attributes(asset_attribute_id);


--
-- TOC entry 4362 (class 2606 OID 39619)
-- Name: asset_attribute_types asset_attribute_types_asset_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_attribute_types
    ADD CONSTRAINT asset_attribute_types_asset_type_id_foreign FOREIGN KEY (asset_type_id) REFERENCES public.asset_type(asset_type_id);


--
-- TOC entry 4363 (class 2606 OID 39636)
-- Name: asset_attribute_values asset_attribute_values_asset_attribute_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_attribute_values
    ADD CONSTRAINT asset_attribute_values_asset_attribute_id_foreign FOREIGN KEY (asset_attribute_id) REFERENCES public.asset_attributes(asset_attribute_id);


--
-- TOC entry 4364 (class 2606 OID 39631)
-- Name: asset_attribute_values asset_attribute_values_asset_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_attribute_values
    ADD CONSTRAINT asset_attribute_values_asset_id_foreign FOREIGN KEY (asset_id) REFERENCES public.assets(asset_id);


--
-- TOC entry 4359 (class 2606 OID 40131)
-- Name: asset_attributes asset_attributes_list_parameter_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_attributes
    ADD CONSTRAINT asset_attributes_list_parameter_id_foreign FOREIGN KEY (list_parameter_id) REFERENCES public.list_parameters(list_parameter_id);


--
-- TOC entry 4360 (class 2606 OID 39602)
-- Name: asset_attributes asset_attributes_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_attributes
    ADD CONSTRAINT asset_attributes_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(user_id);


--
-- TOC entry 4332 (class 2606 OID 40192)
-- Name: asset_checks asset_checks_area_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_checks
    ADD CONSTRAINT asset_checks_area_id_foreign FOREIGN KEY (area_id) REFERENCES public.areas(area_id);


--
-- TOC entry 4333 (class 2606 OID 39422)
-- Name: asset_checks asset_checks_asset_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_checks
    ADD CONSTRAINT asset_checks_asset_id_foreign FOREIGN KEY (asset_id) REFERENCES public.assets(asset_id);


--
-- TOC entry 4334 (class 2606 OID 40197)
-- Name: asset_checks asset_checks_asset_zone_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_checks
    ADD CONSTRAINT asset_checks_asset_zone_id_foreign FOREIGN KEY (asset_zone_id) REFERENCES public.asset_zones(asset_zone_id);


--
-- TOC entry 4335 (class 2606 OID 39417)
-- Name: asset_checks asset_checks_check_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_checks
    ADD CONSTRAINT asset_checks_check_id_foreign FOREIGN KEY (check_id) REFERENCES public.checks(check_id);


--
-- TOC entry 4336 (class 2606 OID 39427)
-- Name: asset_checks asset_checks_plant_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_checks
    ADD CONSTRAINT asset_checks_plant_id_foreign FOREIGN KEY (plant_id) REFERENCES public.plants(plant_id);


--
-- TOC entry 4465 (class 2606 OID 40621)
-- Name: asset_data_source_values asset_data_source_values_asset_data_source_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_data_source_values
    ADD CONSTRAINT asset_data_source_values_asset_data_source_id_foreign FOREIGN KEY (asset_data_source_id) REFERENCES public.asset_data_sources(asset_data_source_id);


--
-- TOC entry 4466 (class 2606 OID 40636)
-- Name: asset_data_source_values asset_data_source_values_asset_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_data_source_values
    ADD CONSTRAINT asset_data_source_values_asset_id_foreign FOREIGN KEY (asset_id) REFERENCES public.assets(asset_id);


--
-- TOC entry 4467 (class 2606 OID 40641)
-- Name: asset_data_source_values asset_data_source_values_asset_zone_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_data_source_values
    ADD CONSTRAINT asset_data_source_values_asset_zone_id_foreign FOREIGN KEY (asset_zone_id) REFERENCES public.asset_zones(asset_zone_id);


--
-- TOC entry 4468 (class 2606 OID 40631)
-- Name: asset_data_source_values asset_data_source_values_data_source_attribute_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_data_source_values
    ADD CONSTRAINT asset_data_source_values_data_source_attribute_id_foreign FOREIGN KEY (data_source_attribute_id) REFERENCES public.data_source_attributes(data_source_attribute_id);


--
-- TOC entry 4469 (class 2606 OID 40626)
-- Name: asset_data_source_values asset_data_source_values_data_source_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_data_source_values
    ADD CONSTRAINT asset_data_source_values_data_source_id_foreign FOREIGN KEY (data_source_id) REFERENCES public.data_sources(data_source_id);


--
-- TOC entry 4425 (class 2606 OID 40282)
-- Name: asset_data_sources asset_data_sources_area_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_data_sources
    ADD CONSTRAINT asset_data_sources_area_id_foreign FOREIGN KEY (area_id) REFERENCES public.areas(area_id);


--
-- TOC entry 4426 (class 2606 OID 40292)
-- Name: asset_data_sources asset_data_sources_asset_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_data_sources
    ADD CONSTRAINT asset_data_sources_asset_id_foreign FOREIGN KEY (asset_id) REFERENCES public.assets(asset_id);


--
-- TOC entry 4427 (class 2606 OID 40297)
-- Name: asset_data_sources asset_data_sources_asset_zone_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_data_sources
    ADD CONSTRAINT asset_data_sources_asset_zone_id_foreign FOREIGN KEY (asset_zone_id) REFERENCES public.asset_zones(asset_zone_id);


--
-- TOC entry 4428 (class 2606 OID 40307)
-- Name: asset_data_sources asset_data_sources_data_source_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_data_sources
    ADD CONSTRAINT asset_data_sources_data_source_id_foreign FOREIGN KEY (data_source_id) REFERENCES public.data_sources(data_source_id);


--
-- TOC entry 4429 (class 2606 OID 40302)
-- Name: asset_data_sources asset_data_sources_data_source_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_data_sources
    ADD CONSTRAINT asset_data_sources_data_source_type_id_foreign FOREIGN KEY (data_source_type_id) REFERENCES public.data_source_types(data_source_type_id);


--
-- TOC entry 4430 (class 2606 OID 40287)
-- Name: asset_data_sources asset_data_sources_plant_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_data_sources
    ADD CONSTRAINT asset_data_sources_plant_id_foreign FOREIGN KEY (plant_id) REFERENCES public.plants(plant_id);


--
-- TOC entry 4446 (class 2606 OID 40486)
-- Name: asset_departments asset_departments_asset_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_departments
    ADD CONSTRAINT asset_departments_asset_id_foreign FOREIGN KEY (asset_id) REFERENCES public.assets(asset_id);


--
-- TOC entry 4447 (class 2606 OID 40491)
-- Name: asset_departments asset_departments_department_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_departments
    ADD CONSTRAINT asset_departments_department_id_foreign FOREIGN KEY (department_id) REFERENCES public.departments(department_id);


--
-- TOC entry 4455 (class 2606 OID 40572)
-- Name: asset_service_values asset_service_values_asset_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_service_values
    ADD CONSTRAINT asset_service_values_asset_id_foreign FOREIGN KEY (asset_id) REFERENCES public.assets(asset_id);


--
-- TOC entry 4456 (class 2606 OID 40557)
-- Name: asset_service_values asset_service_values_asset_service_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_service_values
    ADD CONSTRAINT asset_service_values_asset_service_id_foreign FOREIGN KEY (asset_service_id) REFERENCES public.asset_services(asset_service_id);


--
-- TOC entry 4457 (class 2606 OID 40577)
-- Name: asset_service_values asset_service_values_asset_zone_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_service_values
    ADD CONSTRAINT asset_service_values_asset_zone_id_foreign FOREIGN KEY (asset_zone_id) REFERENCES public.asset_zones(asset_zone_id);


--
-- TOC entry 4458 (class 2606 OID 40567)
-- Name: asset_service_values asset_service_values_service_attribute_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_service_values
    ADD CONSTRAINT asset_service_values_service_attribute_id_foreign FOREIGN KEY (service_attribute_id) REFERENCES public.service_attributes(service_attribute_id);


--
-- TOC entry 4459 (class 2606 OID 40562)
-- Name: asset_service_values asset_service_values_service_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_service_values
    ADD CONSTRAINT asset_service_values_service_id_foreign FOREIGN KEY (service_id) REFERENCES public.services(service_id);


--
-- TOC entry 4371 (class 2606 OID 40217)
-- Name: asset_services asset_services_area_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_services
    ADD CONSTRAINT asset_services_area_id_foreign FOREIGN KEY (area_id) REFERENCES public.areas(area_id);


--
-- TOC entry 4372 (class 2606 OID 39704)
-- Name: asset_services asset_services_asset_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_services
    ADD CONSTRAINT asset_services_asset_id_foreign FOREIGN KEY (asset_id) REFERENCES public.assets(asset_id);


--
-- TOC entry 4373 (class 2606 OID 40222)
-- Name: asset_services asset_services_asset_zone_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_services
    ADD CONSTRAINT asset_services_asset_zone_id_foreign FOREIGN KEY (asset_zone_id) REFERENCES public.asset_zones(asset_zone_id);


--
-- TOC entry 4374 (class 2606 OID 39709)
-- Name: asset_services asset_services_plant_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_services
    ADD CONSTRAINT asset_services_plant_id_foreign FOREIGN KEY (plant_id) REFERENCES public.plants(plant_id);


--
-- TOC entry 4375 (class 2606 OID 39699)
-- Name: asset_services asset_services_service_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_services
    ADD CONSTRAINT asset_services_service_id_foreign FOREIGN KEY (service_id) REFERENCES public.services(service_id);


--
-- TOC entry 4376 (class 2606 OID 40227)
-- Name: asset_services asset_services_service_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_services
    ADD CONSTRAINT asset_services_service_type_id_foreign FOREIGN KEY (service_type_id) REFERENCES public.service_type(service_type_id);


--
-- TOC entry 4450 (class 2606 OID 40540)
-- Name: asset_spare_values asset_spare_values_asset_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_spare_values
    ADD CONSTRAINT asset_spare_values_asset_id_foreign FOREIGN KEY (asset_id) REFERENCES public.assets(asset_id);


--
-- TOC entry 4451 (class 2606 OID 40525)
-- Name: asset_spare_values asset_spare_values_asset_spare_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_spare_values
    ADD CONSTRAINT asset_spare_values_asset_spare_id_foreign FOREIGN KEY (asset_spare_id) REFERENCES public.asset_spares(asset_spare_id);


--
-- TOC entry 4452 (class 2606 OID 40545)
-- Name: asset_spare_values asset_spare_values_asset_zone_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_spare_values
    ADD CONSTRAINT asset_spare_values_asset_zone_id_foreign FOREIGN KEY (asset_zone_id) REFERENCES public.asset_zones(asset_zone_id);


--
-- TOC entry 4453 (class 2606 OID 40535)
-- Name: asset_spare_values asset_spare_values_spare_attribute_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_spare_values
    ADD CONSTRAINT asset_spare_values_spare_attribute_id_foreign FOREIGN KEY (spare_attribute_id) REFERENCES public.spare_attributes(spare_attribute_id);


--
-- TOC entry 4454 (class 2606 OID 40530)
-- Name: asset_spare_values asset_spare_values_spare_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_spare_values
    ADD CONSTRAINT asset_spare_values_spare_id_foreign FOREIGN KEY (spare_id) REFERENCES public.spares(spare_id);


--
-- TOC entry 4326 (class 2606 OID 40202)
-- Name: asset_spares asset_spares_area_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_spares
    ADD CONSTRAINT asset_spares_area_id_foreign FOREIGN KEY (area_id) REFERENCES public.areas(area_id);


--
-- TOC entry 4327 (class 2606 OID 39400)
-- Name: asset_spares asset_spares_asset_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_spares
    ADD CONSTRAINT asset_spares_asset_id_foreign FOREIGN KEY (asset_id) REFERENCES public.assets(asset_id);


--
-- TOC entry 4328 (class 2606 OID 40207)
-- Name: asset_spares asset_spares_asset_zone_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_spares
    ADD CONSTRAINT asset_spares_asset_zone_id_foreign FOREIGN KEY (asset_zone_id) REFERENCES public.asset_zones(asset_zone_id);


--
-- TOC entry 4329 (class 2606 OID 39405)
-- Name: asset_spares asset_spares_plant_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_spares
    ADD CONSTRAINT asset_spares_plant_id_foreign FOREIGN KEY (plant_id) REFERENCES public.plants(plant_id);


--
-- TOC entry 4330 (class 2606 OID 39395)
-- Name: asset_spares asset_spares_spare_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_spares
    ADD CONSTRAINT asset_spares_spare_id_foreign FOREIGN KEY (spare_id) REFERENCES public.spares(spare_id);


--
-- TOC entry 4331 (class 2606 OID 40212)
-- Name: asset_spares asset_spares_spare_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_spares
    ADD CONSTRAINT asset_spares_spare_type_id_foreign FOREIGN KEY (spare_type_id) REFERENCES public.spare_types(spare_type_id);


--
-- TOC entry 4529 (class 2606 OID 41067)
-- Name: asset_template_accessories asset_template_accessories_accessory_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_accessories
    ADD CONSTRAINT asset_template_accessories_accessory_type_id_foreign FOREIGN KEY (accessory_type_id) REFERENCES public.accessory_types(accessory_type_id);


--
-- TOC entry 4530 (class 2606 OID 41047)
-- Name: asset_template_accessories asset_template_accessories_area_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_accessories
    ADD CONSTRAINT asset_template_accessories_area_id_foreign FOREIGN KEY (area_id) REFERENCES public.areas(area_id);


--
-- TOC entry 4531 (class 2606 OID 41057)
-- Name: asset_template_accessories asset_template_accessories_asset_template_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_accessories
    ADD CONSTRAINT asset_template_accessories_asset_template_id_foreign FOREIGN KEY (asset_template_id) REFERENCES public.asset_templates(asset_template_id);


--
-- TOC entry 4532 (class 2606 OID 41052)
-- Name: asset_template_accessories asset_template_accessories_plant_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_accessories
    ADD CONSTRAINT asset_template_accessories_plant_id_foreign FOREIGN KEY (plant_id) REFERENCES public.plants(plant_id);


--
-- TOC entry 4533 (class 2606 OID 41062)
-- Name: asset_template_accessories asset_template_accessories_template_zone_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_accessories
    ADD CONSTRAINT asset_template_accessories_template_zone_id_foreign FOREIGN KEY (template_zone_id) REFERENCES public.template_zones(template_zone_id);


--
-- TOC entry 4491 (class 2606 OID 40808)
-- Name: asset_template_checks asset_template_checks_area_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_checks
    ADD CONSTRAINT asset_template_checks_area_id_foreign FOREIGN KEY (area_id) REFERENCES public.areas(area_id);


--
-- TOC entry 4492 (class 2606 OID 40818)
-- Name: asset_template_checks asset_template_checks_asset_template_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_checks
    ADD CONSTRAINT asset_template_checks_asset_template_id_foreign FOREIGN KEY (asset_template_id) REFERENCES public.asset_templates(asset_template_id);


--
-- TOC entry 4493 (class 2606 OID 40813)
-- Name: asset_template_checks asset_template_checks_check_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_checks
    ADD CONSTRAINT asset_template_checks_check_id_foreign FOREIGN KEY (check_id) REFERENCES public.checks(check_id);


--
-- TOC entry 4494 (class 2606 OID 40828)
-- Name: asset_template_checks asset_template_checks_plant_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_checks
    ADD CONSTRAINT asset_template_checks_plant_id_foreign FOREIGN KEY (plant_id) REFERENCES public.plants(plant_id);


--
-- TOC entry 4495 (class 2606 OID 40823)
-- Name: asset_template_checks asset_template_checks_template_zone_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_checks
    ADD CONSTRAINT asset_template_checks_template_zone_id_foreign FOREIGN KEY (template_zone_id) REFERENCES public.template_zones(template_zone_id);


--
-- TOC entry 4518 (class 2606 OID 40978)
-- Name: asset_template_datasources asset_template_datasources_area_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_datasources
    ADD CONSTRAINT asset_template_datasources_area_id_foreign FOREIGN KEY (area_id) REFERENCES public.areas(area_id);


--
-- TOC entry 4519 (class 2606 OID 40993)
-- Name: asset_template_datasources asset_template_datasources_asset_template_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_datasources
    ADD CONSTRAINT asset_template_datasources_asset_template_id_foreign FOREIGN KEY (asset_template_id) REFERENCES public.asset_templates(asset_template_id);


--
-- TOC entry 4520 (class 2606 OID 41003)
-- Name: asset_template_datasources asset_template_datasources_data_source_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_datasources
    ADD CONSTRAINT asset_template_datasources_data_source_id_foreign FOREIGN KEY (data_source_id) REFERENCES public.data_sources(data_source_id);


--
-- TOC entry 4521 (class 2606 OID 40988)
-- Name: asset_template_datasources asset_template_datasources_data_source_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_datasources
    ADD CONSTRAINT asset_template_datasources_data_source_type_id_foreign FOREIGN KEY (data_source_type_id) REFERENCES public.data_source_types(data_source_type_id);


--
-- TOC entry 4522 (class 2606 OID 40983)
-- Name: asset_template_datasources asset_template_datasources_plant_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_datasources
    ADD CONSTRAINT asset_template_datasources_plant_id_foreign FOREIGN KEY (plant_id) REFERENCES public.plants(plant_id);


--
-- TOC entry 4523 (class 2606 OID 40998)
-- Name: asset_template_datasources asset_template_datasources_template_zone_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_datasources
    ADD CONSTRAINT asset_template_datasources_template_zone_id_foreign FOREIGN KEY (template_zone_id) REFERENCES public.template_zones(template_zone_id);


--
-- TOC entry 4496 (class 2606 OID 40840)
-- Name: asset_template_services asset_template_services_area_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_services
    ADD CONSTRAINT asset_template_services_area_id_foreign FOREIGN KEY (area_id) REFERENCES public.areas(area_id);


--
-- TOC entry 4497 (class 2606 OID 40850)
-- Name: asset_template_services asset_template_services_asset_template_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_services
    ADD CONSTRAINT asset_template_services_asset_template_id_foreign FOREIGN KEY (asset_template_id) REFERENCES public.asset_templates(asset_template_id);


--
-- TOC entry 4498 (class 2606 OID 40860)
-- Name: asset_template_services asset_template_services_plant_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_services
    ADD CONSTRAINT asset_template_services_plant_id_foreign FOREIGN KEY (plant_id) REFERENCES public.plants(plant_id);


--
-- TOC entry 4499 (class 2606 OID 40845)
-- Name: asset_template_services asset_template_services_service_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_services
    ADD CONSTRAINT asset_template_services_service_id_foreign FOREIGN KEY (service_id) REFERENCES public.services(service_id);


--
-- TOC entry 4500 (class 2606 OID 40865)
-- Name: asset_template_services asset_template_services_service_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_services
    ADD CONSTRAINT asset_template_services_service_type_id_foreign FOREIGN KEY (service_type_id) REFERENCES public.service_type(service_type_id);


--
-- TOC entry 4501 (class 2606 OID 40855)
-- Name: asset_template_services asset_template_services_template_zone_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_services
    ADD CONSTRAINT asset_template_services_template_zone_id_foreign FOREIGN KEY (template_zone_id) REFERENCES public.template_zones(template_zone_id);


--
-- TOC entry 4480 (class 2606 OID 40754)
-- Name: asset_template_spares asset_template_spares_area_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_spares
    ADD CONSTRAINT asset_template_spares_area_id_foreign FOREIGN KEY (area_id) REFERENCES public.areas(area_id);


--
-- TOC entry 4481 (class 2606 OID 40744)
-- Name: asset_template_spares asset_template_spares_asset_template_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_spares
    ADD CONSTRAINT asset_template_spares_asset_template_id_foreign FOREIGN KEY (asset_template_id) REFERENCES public.asset_templates(asset_template_id);


--
-- TOC entry 4482 (class 2606 OID 40749)
-- Name: asset_template_spares asset_template_spares_plant_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_spares
    ADD CONSTRAINT asset_template_spares_plant_id_foreign FOREIGN KEY (plant_id) REFERENCES public.plants(plant_id);


--
-- TOC entry 4483 (class 2606 OID 40739)
-- Name: asset_template_spares asset_template_spares_spare_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_spares
    ADD CONSTRAINT asset_template_spares_spare_id_foreign FOREIGN KEY (spare_id) REFERENCES public.spares(spare_id);


--
-- TOC entry 4484 (class 2606 OID 40764)
-- Name: asset_template_spares asset_template_spares_spare_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_spares
    ADD CONSTRAINT asset_template_spares_spare_type_id_foreign FOREIGN KEY (spare_type_id) REFERENCES public.spare_types(spare_type_id);


--
-- TOC entry 4485 (class 2606 OID 40759)
-- Name: asset_template_spares asset_template_spares_template_zone_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_spares
    ADD CONSTRAINT asset_template_spares_template_zone_id_foreign FOREIGN KEY (template_zone_id) REFERENCES public.template_zones(template_zone_id);


--
-- TOC entry 4507 (class 2606 OID 40909)
-- Name: asset_template_variables asset_template_variables_area_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_variables
    ADD CONSTRAINT asset_template_variables_area_id_foreign FOREIGN KEY (area_id) REFERENCES public.areas(area_id);


--
-- TOC entry 4508 (class 2606 OID 40919)
-- Name: asset_template_variables asset_template_variables_asset_template_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_variables
    ADD CONSTRAINT asset_template_variables_asset_template_id_foreign FOREIGN KEY (asset_template_id) REFERENCES public.asset_templates(asset_template_id);


--
-- TOC entry 4509 (class 2606 OID 40914)
-- Name: asset_template_variables asset_template_variables_plant_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_variables
    ADD CONSTRAINT asset_template_variables_plant_id_foreign FOREIGN KEY (plant_id) REFERENCES public.plants(plant_id);


--
-- TOC entry 4510 (class 2606 OID 40924)
-- Name: asset_template_variables asset_template_variables_template_zone_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_variables
    ADD CONSTRAINT asset_template_variables_template_zone_id_foreign FOREIGN KEY (template_zone_id) REFERENCES public.template_zones(template_zone_id);


--
-- TOC entry 4511 (class 2606 OID 40934)
-- Name: asset_template_variables asset_template_variables_variable_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_variables
    ADD CONSTRAINT asset_template_variables_variable_id_foreign FOREIGN KEY (variable_id) REFERENCES public.variables(variable_id);


--
-- TOC entry 4512 (class 2606 OID 40929)
-- Name: asset_template_variables asset_template_variables_variable_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_template_variables
    ADD CONSTRAINT asset_template_variables_variable_type_id_foreign FOREIGN KEY (variable_type_id) REFERENCES public.variable_types(variable_type_id);


--
-- TOC entry 4470 (class 2606 OID 40670)
-- Name: asset_templates asset_templates_area_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_templates
    ADD CONSTRAINT asset_templates_area_id_foreign FOREIGN KEY (area_id) REFERENCES public.areas(area_id);


--
-- TOC entry 4471 (class 2606 OID 40665)
-- Name: asset_templates asset_templates_asset_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_templates
    ADD CONSTRAINT asset_templates_asset_type_id_foreign FOREIGN KEY (asset_type_id) REFERENCES public.asset_type(asset_type_id);


--
-- TOC entry 4472 (class 2606 OID 40680)
-- Name: asset_templates asset_templates_functional_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_templates
    ADD CONSTRAINT asset_templates_functional_id_foreign FOREIGN KEY (functional_id) REFERENCES public.functionals(functional_id);


--
-- TOC entry 4473 (class 2606 OID 40660)
-- Name: asset_templates asset_templates_plant_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_templates
    ADD CONSTRAINT asset_templates_plant_id_foreign FOREIGN KEY (plant_id) REFERENCES public.plants(plant_id);


--
-- TOC entry 4474 (class 2606 OID 40675)
-- Name: asset_templates asset_templates_section_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_templates
    ADD CONSTRAINT asset_templates_section_id_foreign FOREIGN KEY (section_id) REFERENCES public.sections(section_id);


--
-- TOC entry 4460 (class 2606 OID 40604)
-- Name: asset_variable_values asset_variable_values_asset_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_variable_values
    ADD CONSTRAINT asset_variable_values_asset_id_foreign FOREIGN KEY (asset_id) REFERENCES public.assets(asset_id);


--
-- TOC entry 4461 (class 2606 OID 40589)
-- Name: asset_variable_values asset_variable_values_asset_variable_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_variable_values
    ADD CONSTRAINT asset_variable_values_asset_variable_id_foreign FOREIGN KEY (asset_variable_id) REFERENCES public.asset_variables(asset_variable_id);


--
-- TOC entry 4462 (class 2606 OID 40609)
-- Name: asset_variable_values asset_variable_values_asset_zone_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_variable_values
    ADD CONSTRAINT asset_variable_values_asset_zone_id_foreign FOREIGN KEY (asset_zone_id) REFERENCES public.asset_zones(asset_zone_id);


--
-- TOC entry 4463 (class 2606 OID 40599)
-- Name: asset_variable_values asset_variable_values_variable_attribute_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_variable_values
    ADD CONSTRAINT asset_variable_values_variable_attribute_id_foreign FOREIGN KEY (variable_attribute_id) REFERENCES public.variable_attributes(variable_attribute_id);


--
-- TOC entry 4464 (class 2606 OID 40594)
-- Name: asset_variable_values asset_variable_values_variable_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_variable_values
    ADD CONSTRAINT asset_variable_values_variable_id_foreign FOREIGN KEY (variable_id) REFERENCES public.variables(variable_id);


--
-- TOC entry 4419 (class 2606 OID 40239)
-- Name: asset_variables asset_variables_area_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_variables
    ADD CONSTRAINT asset_variables_area_id_foreign FOREIGN KEY (area_id) REFERENCES public.areas(area_id);


--
-- TOC entry 4420 (class 2606 OID 40249)
-- Name: asset_variables asset_variables_asset_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_variables
    ADD CONSTRAINT asset_variables_asset_id_foreign FOREIGN KEY (asset_id) REFERENCES public.assets(asset_id);


--
-- TOC entry 4421 (class 2606 OID 40254)
-- Name: asset_variables asset_variables_asset_zone_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_variables
    ADD CONSTRAINT asset_variables_asset_zone_id_foreign FOREIGN KEY (asset_zone_id) REFERENCES public.asset_zones(asset_zone_id);


--
-- TOC entry 4422 (class 2606 OID 40244)
-- Name: asset_variables asset_variables_plant_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_variables
    ADD CONSTRAINT asset_variables_plant_id_foreign FOREIGN KEY (plant_id) REFERENCES public.plants(plant_id);


--
-- TOC entry 4423 (class 2606 OID 40264)
-- Name: asset_variables asset_variables_variable_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_variables
    ADD CONSTRAINT asset_variables_variable_id_foreign FOREIGN KEY (variable_id) REFERENCES public.variables(variable_id);


--
-- TOC entry 4424 (class 2606 OID 40259)
-- Name: asset_variables asset_variables_variable_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_variables
    ADD CONSTRAINT asset_variables_variable_type_id_foreign FOREIGN KEY (variable_type_id) REFERENCES public.variable_types(variable_type_id);


--
-- TOC entry 4418 (class 2606 OID 40186)
-- Name: asset_zones asset_zones_asset_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asset_zones
    ADD CONSTRAINT asset_zones_asset_id_foreign FOREIGN KEY (asset_id) REFERENCES public.assets(asset_id);


--
-- TOC entry 4320 (class 2606 OID 40169)
-- Name: assets assets_area_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assets
    ADD CONSTRAINT assets_area_id_foreign FOREIGN KEY (area_id) REFERENCES public.areas(area_id);


--
-- TOC entry 4321 (class 2606 OID 41078)
-- Name: assets assets_asset_template_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assets
    ADD CONSTRAINT assets_asset_template_id_foreign FOREIGN KEY (asset_template_id) REFERENCES public.asset_templates(asset_template_id);


--
-- TOC entry 4322 (class 2606 OID 39383)
-- Name: assets assets_asset_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assets
    ADD CONSTRAINT assets_asset_type_id_foreign FOREIGN KEY (asset_type_id) REFERENCES public.asset_type(asset_type_id);


--
-- TOC entry 4323 (class 2606 OID 40174)
-- Name: assets assets_functional_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assets
    ADD CONSTRAINT assets_functional_id_foreign FOREIGN KEY (functional_id) REFERENCES public.functionals(functional_id);


--
-- TOC entry 4324 (class 2606 OID 39378)
-- Name: assets assets_plant_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assets
    ADD CONSTRAINT assets_plant_id_foreign FOREIGN KEY (plant_id) REFERENCES public.plants(plant_id);


--
-- TOC entry 4325 (class 2606 OID 39731)
-- Name: assets assets_section_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assets
    ADD CONSTRAINT assets_section_id_foreign FOREIGN KEY (section_id) REFERENCES public.sections(section_id);


--
-- TOC entry 4395 (class 2606 OID 39932)
-- Name: break_down_attribute_types break_down_attribute_types_break_down_attribute_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.break_down_attribute_types
    ADD CONSTRAINT break_down_attribute_types_break_down_attribute_id_foreign FOREIGN KEY (break_down_attribute_id) REFERENCES public.break_down_attributes(break_down_attribute_id);


--
-- TOC entry 4396 (class 2606 OID 39937)
-- Name: break_down_attribute_types break_down_attribute_types_break_down_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.break_down_attribute_types
    ADD CONSTRAINT break_down_attribute_types_break_down_type_id_foreign FOREIGN KEY (break_down_type_id) REFERENCES public.break_down_types(break_down_type_id);


--
-- TOC entry 4411 (class 2606 OID 40075)
-- Name: break_down_attribute_values break_down_attribute_values_break_down_attribute_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.break_down_attribute_values
    ADD CONSTRAINT break_down_attribute_values_break_down_attribute_id_foreign FOREIGN KEY (break_down_attribute_id) REFERENCES public.break_down_attributes(break_down_attribute_id);


--
-- TOC entry 4412 (class 2606 OID 40070)
-- Name: break_down_attribute_values break_down_attribute_values_break_down_list_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.break_down_attribute_values
    ADD CONSTRAINT break_down_attribute_values_break_down_list_id_foreign FOREIGN KEY (break_down_list_id) REFERENCES public.break_down_lists(break_down_list_id);


--
-- TOC entry 4385 (class 2606 OID 40111)
-- Name: break_down_attributes break_down_attributes_list_parameter_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.break_down_attributes
    ADD CONSTRAINT break_down_attributes_list_parameter_id_foreign FOREIGN KEY (list_parameter_id) REFERENCES public.list_parameters(list_parameter_id);


--
-- TOC entry 4386 (class 2606 OID 39852)
-- Name: break_down_attributes break_down_attributes_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.break_down_attributes
    ADD CONSTRAINT break_down_attributes_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(user_id);


--
-- TOC entry 4403 (class 2606 OID 40435)
-- Name: break_down_lists break_down_lists_asset_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.break_down_lists
    ADD CONSTRAINT break_down_lists_asset_id_foreign FOREIGN KEY (asset_id) REFERENCES public.assets(asset_id);


--
-- TOC entry 4404 (class 2606 OID 40007)
-- Name: break_down_lists break_down_lists_break_down_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.break_down_lists
    ADD CONSTRAINT break_down_lists_break_down_type_id_foreign FOREIGN KEY (break_down_type_id) REFERENCES public.break_down_types(break_down_type_id);


--
-- TOC entry 4416 (class 2606 OID 40164)
-- Name: campaign_results campaign_results_asset_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.campaign_results
    ADD CONSTRAINT campaign_results_asset_id_foreign FOREIGN KEY (asset_id) REFERENCES public.assets(asset_id);


--
-- TOC entry 4417 (class 2606 OID 40159)
-- Name: campaign_results campaign_results_campaign_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.campaign_results
    ADD CONSTRAINT campaign_results_campaign_id_foreign FOREIGN KEY (campaign_id) REFERENCES public.campaigns(campaign_id);


--
-- TOC entry 4415 (class 2606 OID 40145)
-- Name: campaigns campaigns_asset_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.campaigns
    ADD CONSTRAINT campaigns_asset_id_foreign FOREIGN KEY (asset_id) REFERENCES public.assets(asset_id);


--
-- TOC entry 4365 (class 2606 OID 39648)
-- Name: check_asset_types check_asset_types_asset_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.check_asset_types
    ADD CONSTRAINT check_asset_types_asset_type_id_foreign FOREIGN KEY (asset_type_id) REFERENCES public.asset_type(asset_type_id);


--
-- TOC entry 4366 (class 2606 OID 39653)
-- Name: check_asset_types check_asset_types_check_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.check_asset_types
    ADD CONSTRAINT check_asset_types_check_id_foreign FOREIGN KEY (check_id) REFERENCES public.checks(check_id);


--
-- TOC entry 4313 (class 2606 OID 40413)
-- Name: checks checks_department_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.checks
    ADD CONSTRAINT checks_department_id_foreign FOREIGN KEY (department_id) REFERENCES public.departments(department_id);


--
-- TOC entry 4358 (class 2606 OID 39587)
-- Name: consents consents_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.consents
    ADD CONSTRAINT consents_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(user_id);


--
-- TOC entry 4401 (class 2606 OID 39990)
-- Name: data_source_asset_types data_source_asset_types_asset_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.data_source_asset_types
    ADD CONSTRAINT data_source_asset_types_asset_type_id_foreign FOREIGN KEY (asset_type_id) REFERENCES public.asset_type(asset_type_id);


--
-- TOC entry 4402 (class 2606 OID 39995)
-- Name: data_source_asset_types data_source_asset_types_data_source_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.data_source_asset_types
    ADD CONSTRAINT data_source_asset_types_data_source_id_foreign FOREIGN KEY (data_source_id) REFERENCES public.data_sources(data_source_id);


--
-- TOC entry 4389 (class 2606 OID 39881)
-- Name: data_source_attribute_types data_source_attribute_types_data_source_attribute_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.data_source_attribute_types
    ADD CONSTRAINT data_source_attribute_types_data_source_attribute_id_foreign FOREIGN KEY (data_source_attribute_id) REFERENCES public.data_source_attributes(data_source_attribute_id);


--
-- TOC entry 4390 (class 2606 OID 39886)
-- Name: data_source_attribute_types data_source_attribute_types_data_source_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.data_source_attribute_types
    ADD CONSTRAINT data_source_attribute_types_data_source_type_id_foreign FOREIGN KEY (data_source_type_id) REFERENCES public.data_source_types(data_source_type_id);


--
-- TOC entry 4413 (class 2606 OID 40092)
-- Name: data_source_attribute_values data_source_attribute_values_data_source_attribute_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.data_source_attribute_values
    ADD CONSTRAINT data_source_attribute_values_data_source_attribute_id_foreign FOREIGN KEY (data_source_attribute_id) REFERENCES public.data_source_attributes(data_source_attribute_id);


--
-- TOC entry 4414 (class 2606 OID 40087)
-- Name: data_source_attribute_values data_source_attribute_values_data_source_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.data_source_attribute_values
    ADD CONSTRAINT data_source_attribute_values_data_source_id_foreign FOREIGN KEY (data_source_id) REFERENCES public.data_sources(data_source_id);


--
-- TOC entry 4379 (class 2606 OID 40116)
-- Name: data_source_attributes data_source_attributes_list_parameter_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.data_source_attributes
    ADD CONSTRAINT data_source_attributes_list_parameter_id_foreign FOREIGN KEY (list_parameter_id) REFERENCES public.list_parameters(list_parameter_id);


--
-- TOC entry 4380 (class 2606 OID 39807)
-- Name: data_source_attributes data_source_attributes_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.data_source_attributes
    ADD CONSTRAINT data_source_attributes_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(user_id);


--
-- TOC entry 4400 (class 2606 OID 39978)
-- Name: data_sources data_sources_data_source_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.data_sources
    ADD CONSTRAINT data_sources_data_source_type_id_foreign FOREIGN KEY (data_source_type_id) REFERENCES public.data_source_types(data_source_type_id);


--
-- TOC entry 4534 (class 2606 OID 41092)
-- Name: downloaded_reports downloaded_reports_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.downloaded_reports
    ADD CONSTRAINT downloaded_reports_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(user_id);


--
-- TOC entry 4317 (class 2606 OID 39347)
-- Name: equipment equipment_equipment_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.equipment
    ADD CONSTRAINT equipment_equipment_type_id_foreign FOREIGN KEY (equipment_type_id) REFERENCES public.equipment_types(equipment_type_id);


--
-- TOC entry 4318 (class 2606 OID 39342)
-- Name: equipment equipment_plant_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.equipment
    ADD CONSTRAINT equipment_plant_id_foreign FOREIGN KEY (plant_id) REFERENCES public.plants(plant_id);


--
-- TOC entry 4312 (class 2606 OID 39213)
-- Name: otps otps_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.otps
    ADD CONSTRAINT otps_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(user_id);


--
-- TOC entry 4308 (class 2606 OID 39155)
-- Name: plants plants_area_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.plants
    ADD CONSTRAINT plants_area_id_foreign FOREIGN KEY (area_id) REFERENCES public.areas(area_id);


--
-- TOC entry 4315 (class 2606 OID 39328)
-- Name: role_abilities role_abilities_ability_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.role_abilities
    ADD CONSTRAINT role_abilities_ability_id_foreign FOREIGN KEY (ability_id) REFERENCES public.abilities(ability_id);


--
-- TOC entry 4316 (class 2606 OID 39323)
-- Name: role_abilities role_abilities_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.role_abilities
    ADD CONSTRAINT role_abilities_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(role_id);


--
-- TOC entry 4369 (class 2606 OID 39682)
-- Name: service_asset_types service_asset_types_asset_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.service_asset_types
    ADD CONSTRAINT service_asset_types_asset_type_id_foreign FOREIGN KEY (asset_type_id) REFERENCES public.asset_type(asset_type_id);


--
-- TOC entry 4370 (class 2606 OID 39687)
-- Name: service_asset_types service_asset_types_service_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.service_asset_types
    ADD CONSTRAINT service_asset_types_service_id_foreign FOREIGN KEY (service_id) REFERENCES public.services(service_id);


--
-- TOC entry 4393 (class 2606 OID 39915)
-- Name: service_attribute_types service_attribute_types_service_attribute_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.service_attribute_types
    ADD CONSTRAINT service_attribute_types_service_attribute_id_foreign FOREIGN KEY (service_attribute_id) REFERENCES public.service_attributes(service_attribute_id);


--
-- TOC entry 4394 (class 2606 OID 39920)
-- Name: service_attribute_types service_attribute_types_service_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.service_attribute_types
    ADD CONSTRAINT service_attribute_types_service_type_id_foreign FOREIGN KEY (service_type_id) REFERENCES public.service_type(service_type_id);


--
-- TOC entry 4407 (class 2606 OID 40041)
-- Name: service_attribute_values service_attribute_values_service_attribute_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.service_attribute_values
    ADD CONSTRAINT service_attribute_values_service_attribute_id_foreign FOREIGN KEY (service_attribute_id) REFERENCES public.service_attributes(service_attribute_id);


--
-- TOC entry 4408 (class 2606 OID 40036)
-- Name: service_attribute_values service_attribute_values_service_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.service_attribute_values
    ADD CONSTRAINT service_attribute_values_service_id_foreign FOREIGN KEY (service_id) REFERENCES public.services(service_id);


--
-- TOC entry 4383 (class 2606 OID 40126)
-- Name: service_attributes service_attributes_list_parameter_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.service_attributes
    ADD CONSTRAINT service_attributes_list_parameter_id_foreign FOREIGN KEY (list_parameter_id) REFERENCES public.list_parameters(list_parameter_id);


--
-- TOC entry 4384 (class 2606 OID 39837)
-- Name: service_attributes service_attributes_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.service_attributes
    ADD CONSTRAINT service_attributes_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(user_id);


--
-- TOC entry 4367 (class 2606 OID 39665)
-- Name: spare_asset_types spare_asset_types_asset_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.spare_asset_types
    ADD CONSTRAINT spare_asset_types_asset_type_id_foreign FOREIGN KEY (asset_type_id) REFERENCES public.asset_type(asset_type_id);


--
-- TOC entry 4368 (class 2606 OID 39670)
-- Name: spare_asset_types spare_asset_types_spare_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.spare_asset_types
    ADD CONSTRAINT spare_asset_types_spare_id_foreign FOREIGN KEY (spare_id) REFERENCES public.spares(spare_id);


--
-- TOC entry 4387 (class 2606 OID 39864)
-- Name: spare_attribute_types spare_attribute_types_spare_attribute_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.spare_attribute_types
    ADD CONSTRAINT spare_attribute_types_spare_attribute_id_foreign FOREIGN KEY (spare_attribute_id) REFERENCES public.spare_attributes(spare_attribute_id);


--
-- TOC entry 4388 (class 2606 OID 39869)
-- Name: spare_attribute_types spare_attribute_types_spare_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.spare_attribute_types
    ADD CONSTRAINT spare_attribute_types_spare_type_id_foreign FOREIGN KEY (spare_type_id) REFERENCES public.spare_types(spare_type_id);


--
-- TOC entry 4405 (class 2606 OID 40024)
-- Name: spare_attribute_values spare_attribute_values_spare_attribute_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.spare_attribute_values
    ADD CONSTRAINT spare_attribute_values_spare_attribute_id_foreign FOREIGN KEY (spare_attribute_id) REFERENCES public.spare_attributes(spare_attribute_id);


--
-- TOC entry 4406 (class 2606 OID 40019)
-- Name: spare_attribute_values spare_attribute_values_spare_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.spare_attribute_values
    ADD CONSTRAINT spare_attribute_values_spare_id_foreign FOREIGN KEY (spare_id) REFERENCES public.spares(spare_id);


--
-- TOC entry 4377 (class 2606 OID 40121)
-- Name: spare_attributes spare_attributes_list_parameter_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.spare_attributes
    ADD CONSTRAINT spare_attributes_list_parameter_id_foreign FOREIGN KEY (list_parameter_id) REFERENCES public.list_parameters(list_parameter_id);


--
-- TOC entry 4378 (class 2606 OID 39792)
-- Name: spare_attributes spare_attributes_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.spare_attributes
    ADD CONSTRAINT spare_attributes_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(user_id);


--
-- TOC entry 4319 (class 2606 OID 39359)
-- Name: spares spares_spare_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.spares
    ADD CONSTRAINT spares_spare_type_id_foreign FOREIGN KEY (spare_type_id) REFERENCES public.spare_types(spare_type_id);


--
-- TOC entry 4478 (class 2606 OID 40727)
-- Name: template_attribute_values template_attribute_values_asset_attribute_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.template_attribute_values
    ADD CONSTRAINT template_attribute_values_asset_attribute_id_foreign FOREIGN KEY (asset_attribute_id) REFERENCES public.asset_attributes(asset_attribute_id);


--
-- TOC entry 4479 (class 2606 OID 40722)
-- Name: template_attribute_values template_attribute_values_asset_template_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.template_attribute_values
    ADD CONSTRAINT template_attribute_values_asset_template_id_foreign FOREIGN KEY (asset_template_id) REFERENCES public.asset_templates(asset_template_id);


--
-- TOC entry 4524 (class 2606 OID 41020)
-- Name: template_datasource_values template_datasource_values_asset_template_datasource_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.template_datasource_values
    ADD CONSTRAINT template_datasource_values_asset_template_datasource_id_foreign FOREIGN KEY (asset_template_datasource_id) REFERENCES public.asset_template_datasources(asset_template_datasource_id);


--
-- TOC entry 4525 (class 2606 OID 41025)
-- Name: template_datasource_values template_datasource_values_asset_template_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.template_datasource_values
    ADD CONSTRAINT template_datasource_values_asset_template_id_foreign FOREIGN KEY (asset_template_id) REFERENCES public.asset_templates(asset_template_id);


--
-- TOC entry 4526 (class 2606 OID 41015)
-- Name: template_datasource_values template_datasource_values_data_source_attribute_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.template_datasource_values
    ADD CONSTRAINT template_datasource_values_data_source_attribute_id_foreign FOREIGN KEY (data_source_attribute_id) REFERENCES public.data_source_attributes(data_source_attribute_id);


--
-- TOC entry 4527 (class 2606 OID 41035)
-- Name: template_datasource_values template_datasource_values_data_source_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.template_datasource_values
    ADD CONSTRAINT template_datasource_values_data_source_id_foreign FOREIGN KEY (data_source_id) REFERENCES public.data_sources(data_source_id);


--
-- TOC entry 4528 (class 2606 OID 41030)
-- Name: template_datasource_values template_datasource_values_template_zone_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.template_datasource_values
    ADD CONSTRAINT template_datasource_values_template_zone_id_foreign FOREIGN KEY (template_zone_id) REFERENCES public.template_zones(template_zone_id);


--
-- TOC entry 4476 (class 2606 OID 40705)
-- Name: template_departments template_departments_asset_template_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.template_departments
    ADD CONSTRAINT template_departments_asset_template_id_foreign FOREIGN KEY (asset_template_id) REFERENCES public.asset_templates(asset_template_id);


--
-- TOC entry 4477 (class 2606 OID 40710)
-- Name: template_departments template_departments_department_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.template_departments
    ADD CONSTRAINT template_departments_department_id_foreign FOREIGN KEY (department_id) REFERENCES public.departments(department_id);


--
-- TOC entry 4502 (class 2606 OID 40887)
-- Name: template_service_values template_service_values_asset_template_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.template_service_values
    ADD CONSTRAINT template_service_values_asset_template_id_foreign FOREIGN KEY (asset_template_id) REFERENCES public.asset_templates(asset_template_id);


--
-- TOC entry 4503 (class 2606 OID 40877)
-- Name: template_service_values template_service_values_asset_template_service_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.template_service_values
    ADD CONSTRAINT template_service_values_asset_template_service_id_foreign FOREIGN KEY (asset_template_service_id) REFERENCES public.asset_template_services(asset_template_service_id);


--
-- TOC entry 4504 (class 2606 OID 40897)
-- Name: template_service_values template_service_values_service_attribute_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.template_service_values
    ADD CONSTRAINT template_service_values_service_attribute_id_foreign FOREIGN KEY (service_attribute_id) REFERENCES public.service_attributes(service_attribute_id);


--
-- TOC entry 4505 (class 2606 OID 40882)
-- Name: template_service_values template_service_values_service_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.template_service_values
    ADD CONSTRAINT template_service_values_service_id_foreign FOREIGN KEY (service_id) REFERENCES public.services(service_id);


--
-- TOC entry 4506 (class 2606 OID 40892)
-- Name: template_service_values template_service_values_template_zone_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.template_service_values
    ADD CONSTRAINT template_service_values_template_zone_id_foreign FOREIGN KEY (template_zone_id) REFERENCES public.template_zones(template_zone_id);


--
-- TOC entry 4486 (class 2606 OID 40781)
-- Name: template_spare_values template_spare_values_asset_template_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.template_spare_values
    ADD CONSTRAINT template_spare_values_asset_template_id_foreign FOREIGN KEY (asset_template_id) REFERENCES public.asset_templates(asset_template_id);


--
-- TOC entry 4487 (class 2606 OID 40786)
-- Name: template_spare_values template_spare_values_asset_template_spare_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.template_spare_values
    ADD CONSTRAINT template_spare_values_asset_template_spare_id_foreign FOREIGN KEY (asset_template_spare_id) REFERENCES public.asset_template_spares(asset_template_spare_id);


--
-- TOC entry 4488 (class 2606 OID 40796)
-- Name: template_spare_values template_spare_values_spare_attribute_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.template_spare_values
    ADD CONSTRAINT template_spare_values_spare_attribute_id_foreign FOREIGN KEY (spare_attribute_id) REFERENCES public.spare_attributes(spare_attribute_id);


--
-- TOC entry 4489 (class 2606 OID 40776)
-- Name: template_spare_values template_spare_values_spare_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.template_spare_values
    ADD CONSTRAINT template_spare_values_spare_id_foreign FOREIGN KEY (spare_id) REFERENCES public.spares(spare_id);


--
-- TOC entry 4490 (class 2606 OID 40791)
-- Name: template_spare_values template_spare_values_template_zone_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.template_spare_values
    ADD CONSTRAINT template_spare_values_template_zone_id_foreign FOREIGN KEY (template_zone_id) REFERENCES public.template_zones(template_zone_id);


--
-- TOC entry 4513 (class 2606 OID 40961)
-- Name: template_variable_values template_variable_values_asset_template_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.template_variable_values
    ADD CONSTRAINT template_variable_values_asset_template_id_foreign FOREIGN KEY (asset_template_id) REFERENCES public.asset_templates(asset_template_id);


--
-- TOC entry 4514 (class 2606 OID 40946)
-- Name: template_variable_values template_variable_values_asset_template_variable_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.template_variable_values
    ADD CONSTRAINT template_variable_values_asset_template_variable_id_foreign FOREIGN KEY (asset_template_variable_id) REFERENCES public.asset_template_variables(asset_template_variable_id);


--
-- TOC entry 4515 (class 2606 OID 40966)
-- Name: template_variable_values template_variable_values_template_zone_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.template_variable_values
    ADD CONSTRAINT template_variable_values_template_zone_id_foreign FOREIGN KEY (template_zone_id) REFERENCES public.template_zones(template_zone_id);


--
-- TOC entry 4516 (class 2606 OID 40956)
-- Name: template_variable_values template_variable_values_variable_attribute_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.template_variable_values
    ADD CONSTRAINT template_variable_values_variable_attribute_id_foreign FOREIGN KEY (variable_attribute_id) REFERENCES public.variable_attributes(variable_attribute_id);


--
-- TOC entry 4517 (class 2606 OID 40951)
-- Name: template_variable_values template_variable_values_variable_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.template_variable_values
    ADD CONSTRAINT template_variable_values_variable_id_foreign FOREIGN KEY (variable_id) REFERENCES public.variables(variable_id);


--
-- TOC entry 4475 (class 2606 OID 40692)
-- Name: template_zones template_zones_asset_template_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.template_zones
    ADD CONSTRAINT template_zones_asset_template_id_foreign FOREIGN KEY (asset_template_id) REFERENCES public.asset_templates(asset_template_id);


--
-- TOC entry 4337 (class 2606 OID 39453)
-- Name: user_activities user_activities_asset_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_activities
    ADD CONSTRAINT user_activities_asset_id_foreign FOREIGN KEY (asset_id) REFERENCES public.assets(asset_id);


--
-- TOC entry 4338 (class 2606 OID 39575)
-- Name: user_activities user_activities_plant_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_activities
    ADD CONSTRAINT user_activities_plant_id_foreign FOREIGN KEY (plant_id) REFERENCES public.plants(plant_id);


--
-- TOC entry 4339 (class 2606 OID 39458)
-- Name: user_activities user_activities_reason_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_activities
    ADD CONSTRAINT user_activities_reason_id_foreign FOREIGN KEY (reason_id) REFERENCES public.reasons(reason_id);


--
-- TOC entry 4340 (class 2606 OID 39448)
-- Name: user_activities user_activities_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_activities
    ADD CONSTRAINT user_activities_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(user_id);


--
-- TOC entry 4353 (class 2606 OID 39558)
-- Name: user_asset_checks user_asset_checks_asset_check_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_asset_checks
    ADD CONSTRAINT user_asset_checks_asset_check_id_foreign FOREIGN KEY (asset_check_id) REFERENCES public.asset_checks(asset_check_id);


--
-- TOC entry 4354 (class 2606 OID 39553)
-- Name: user_asset_checks user_asset_checks_check_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_asset_checks
    ADD CONSTRAINT user_asset_checks_check_id_foreign FOREIGN KEY (check_id) REFERENCES public.checks(check_id);


--
-- TOC entry 4355 (class 2606 OID 41097)
-- Name: user_asset_checks user_asset_checks_remark_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_asset_checks
    ADD CONSTRAINT user_asset_checks_remark_user_id_foreign FOREIGN KEY (remark_user_id) REFERENCES public.users(user_id);


--
-- TOC entry 4356 (class 2606 OID 39548)
-- Name: user_asset_checks user_asset_checks_user_check_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_asset_checks
    ADD CONSTRAINT user_asset_checks_user_check_id_foreign FOREIGN KEY (user_check_id) REFERENCES public.user_checks(user_check_id);


--
-- TOC entry 4439 (class 2606 OID 40646)
-- Name: user_asset_variables user_asset_variables_asset_zone_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_asset_variables
    ADD CONSTRAINT user_asset_variables_asset_zone_id_foreign FOREIGN KEY (asset_zone_id) REFERENCES public.asset_zones(asset_zone_id);


--
-- TOC entry 4440 (class 2606 OID 40403)
-- Name: user_asset_variables user_asset_variables_user_variable_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_asset_variables
    ADD CONSTRAINT user_asset_variables_user_variable_id_foreign FOREIGN KEY (user_variable_id) REFERENCES public.user_variables(user_variable_id);


--
-- TOC entry 4441 (class 2606 OID 40408)
-- Name: user_asset_variables user_asset_variables_variable_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_asset_variables
    ADD CONSTRAINT user_asset_variables_variable_id_foreign FOREIGN KEY (variable_id) REFERENCES public.variables(variable_id);


--
-- TOC entry 4357 (class 2606 OID 39570)
-- Name: user_check_attachments user_check_attachments_user_check_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_check_attachments
    ADD CONSTRAINT user_check_attachments_user_check_id_foreign FOREIGN KEY (user_check_id) REFERENCES public.user_checks(user_check_id);


--
-- TOC entry 4348 (class 2606 OID 39533)
-- Name: user_checks user_checks_asset_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_checks
    ADD CONSTRAINT user_checks_asset_id_foreign FOREIGN KEY (asset_id) REFERENCES public.assets(asset_id);


--
-- TOC entry 4349 (class 2606 OID 40362)
-- Name: user_checks user_checks_asset_zone_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_checks
    ADD CONSTRAINT user_checks_asset_zone_id_foreign FOREIGN KEY (asset_zone_id) REFERENCES public.asset_zones(asset_zone_id);


--
-- TOC entry 4350 (class 2606 OID 40430)
-- Name: user_checks user_checks_department_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_checks
    ADD CONSTRAINT user_checks_department_id_foreign FOREIGN KEY (department_id) REFERENCES public.departments(department_id);


--
-- TOC entry 4351 (class 2606 OID 39523)
-- Name: user_checks user_checks_plant_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_checks
    ADD CONSTRAINT user_checks_plant_id_foreign FOREIGN KEY (plant_id) REFERENCES public.plants(plant_id);


--
-- TOC entry 4352 (class 2606 OID 39528)
-- Name: user_checks user_checks_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_checks
    ADD CONSTRAINT user_checks_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(user_id);


--
-- TOC entry 4341 (class 2606 OID 39492)
-- Name: user_services user_services_asset_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_services
    ADD CONSTRAINT user_services_asset_id_foreign FOREIGN KEY (asset_id) REFERENCES public.assets(asset_id);


--
-- TOC entry 4342 (class 2606 OID 39477)
-- Name: user_services user_services_plant_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_services
    ADD CONSTRAINT user_services_plant_id_foreign FOREIGN KEY (plant_id) REFERENCES public.plants(plant_id);


--
-- TOC entry 4343 (class 2606 OID 39487)
-- Name: user_services user_services_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_services
    ADD CONSTRAINT user_services_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(user_id);


--
-- TOC entry 4344 (class 2606 OID 40423)
-- Name: user_spares user_spares_asset_zone_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_spares
    ADD CONSTRAINT user_spares_asset_zone_id_foreign FOREIGN KEY (asset_zone_id) REFERENCES public.asset_zones(asset_zone_id);


--
-- TOC entry 4345 (class 2606 OID 40418)
-- Name: user_spares user_spares_service_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_spares
    ADD CONSTRAINT user_spares_service_id_foreign FOREIGN KEY (service_id) REFERENCES public.services(service_id);


--
-- TOC entry 4346 (class 2606 OID 39509)
-- Name: user_spares user_spares_spare_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_spares
    ADD CONSTRAINT user_spares_spare_id_foreign FOREIGN KEY (spare_id) REFERENCES public.spares(spare_id);


--
-- TOC entry 4347 (class 2606 OID 39504)
-- Name: user_spares user_spares_user_service_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_spares
    ADD CONSTRAINT user_spares_user_service_id_foreign FOREIGN KEY (user_service_id) REFERENCES public.user_services(user_service_id);


--
-- TOC entry 4436 (class 2606 OID 40391)
-- Name: user_variables user_variables_asset_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_variables
    ADD CONSTRAINT user_variables_asset_id_foreign FOREIGN KEY (asset_id) REFERENCES public.assets(asset_id);


--
-- TOC entry 4437 (class 2606 OID 40381)
-- Name: user_variables user_variables_plant_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_variables
    ADD CONSTRAINT user_variables_plant_id_foreign FOREIGN KEY (plant_id) REFERENCES public.plants(plant_id);


--
-- TOC entry 4438 (class 2606 OID 40386)
-- Name: user_variables user_variables_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_variables
    ADD CONSTRAINT user_variables_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(user_id);


--
-- TOC entry 4309 (class 2606 OID 41103)
-- Name: users users_department_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_department_id_foreign FOREIGN KEY (department_id) REFERENCES public.departments(department_id);


--
-- TOC entry 4310 (class 2606 OID 39178)
-- Name: users users_plant_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_plant_id_foreign FOREIGN KEY (plant_id) REFERENCES public.plants(plant_id);


--
-- TOC entry 4311 (class 2606 OID 39183)
-- Name: users users_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(role_id);


--
-- TOC entry 4398 (class 2606 OID 39961)
-- Name: variable_asset_types variable_asset_types_asset_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.variable_asset_types
    ADD CONSTRAINT variable_asset_types_asset_type_id_foreign FOREIGN KEY (asset_type_id) REFERENCES public.asset_type(asset_type_id);


--
-- TOC entry 4399 (class 2606 OID 39966)
-- Name: variable_asset_types variable_asset_types_variable_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.variable_asset_types
    ADD CONSTRAINT variable_asset_types_variable_id_foreign FOREIGN KEY (variable_id) REFERENCES public.variables(variable_id);


--
-- TOC entry 4391 (class 2606 OID 39898)
-- Name: variable_attribute_types variable_attribute_types_variable_attribute_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.variable_attribute_types
    ADD CONSTRAINT variable_attribute_types_variable_attribute_id_foreign FOREIGN KEY (variable_attribute_id) REFERENCES public.variable_attributes(variable_attribute_id);


--
-- TOC entry 4392 (class 2606 OID 39903)
-- Name: variable_attribute_types variable_attribute_types_variable_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.variable_attribute_types
    ADD CONSTRAINT variable_attribute_types_variable_type_id_foreign FOREIGN KEY (variable_type_id) REFERENCES public.variable_types(variable_type_id);


--
-- TOC entry 4409 (class 2606 OID 40058)
-- Name: variable_attribute_values variable_attribute_values_variable_attribute_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.variable_attribute_values
    ADD CONSTRAINT variable_attribute_values_variable_attribute_id_foreign FOREIGN KEY (variable_attribute_id) REFERENCES public.variable_attributes(variable_attribute_id);


--
-- TOC entry 4410 (class 2606 OID 40053)
-- Name: variable_attribute_values variable_attribute_values_variable_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.variable_attribute_values
    ADD CONSTRAINT variable_attribute_values_variable_id_foreign FOREIGN KEY (variable_id) REFERENCES public.variables(variable_id);


--
-- TOC entry 4381 (class 2606 OID 40106)
-- Name: variable_attributes variable_attributes_list_parameter_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.variable_attributes
    ADD CONSTRAINT variable_attributes_list_parameter_id_foreign FOREIGN KEY (list_parameter_id) REFERENCES public.list_parameters(list_parameter_id);


--
-- TOC entry 4382 (class 2606 OID 39822)
-- Name: variable_attributes variable_attributes_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.variable_attributes
    ADD CONSTRAINT variable_attributes_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(user_id);


--
-- TOC entry 4397 (class 2606 OID 39949)
-- Name: variables variables_variable_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.variables
    ADD CONSTRAINT variables_variable_type_id_foreign FOREIGN KEY (variable_type_id) REFERENCES public.variable_types(variable_type_id);


-- Completed on 2025-01-28 22:59:19 IST

--
-- PostgreSQL database dump complete
--

