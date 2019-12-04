import VueRouter from "vue-router";
import Decks from "./decks/Decks";
import Deck from "./deck/Deck";
import Login from "./auth/Login"
import Register from "./auth/Register"

const routes = [{
        path: "/",
        component: Decks,
        name: "home",
    },
    {
        path: "/login",
        component: Login,
        name: "login",
    },
    {
        path: "/register",
        component: Register,
        name: "register",
    },
    {
        path: "/deck/:id",
        component: Deck,
        name: "deck"
    },
];

const router = new VueRouter({
    mode: 'history',
    routes // short for `routes: routes`
});

export default router;
