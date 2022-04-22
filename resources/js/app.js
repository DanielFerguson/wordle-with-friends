require("./bootstrap");

import Alpine from "alpinejs";
import React from "react";
import { render } from "react-dom";
import { createInertiaApp } from "@inertiajs/inertia-react";

window.Alpine = Alpine;
Alpine.start();

createInertiaApp({
    resolve: (name) => require(`./Pages/${name}`),
    setup({ el, App, props }) {
        render(<App {...props} />, el);
    },
});
