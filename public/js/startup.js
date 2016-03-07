require(
    [
        "vue",
    ],
    function(Vue) {
        new Vue({})

        var MyComponent = Vue.extend({
            template: '<div>A custom component!</div>'
        })

        Vue.component('my-component', MyComponent);

        new Vue({
            el: '#example'
        })
    }
);