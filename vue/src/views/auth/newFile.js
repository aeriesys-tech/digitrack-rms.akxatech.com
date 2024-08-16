/* __placeholder__ */
export default (await import('vue')).defineComponent({
mounted() {
this.$store.dispatch("logout");
},

methods: {
login() {
this.$store.dispatch("setUser", { data: { user_id: 1, name: "Rajiv" } });
this.$router.push('/dashboard');
}
}
});
