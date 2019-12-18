import VueRouter from "vue-router";
import Decks from "./decks/Decks";
import Deck from "./deck/Deck";
import Login from "./auth/Login"
import Register from "./auth/Register"
import Logout from "./auth/Logout"
import DeckEditor from "./editor/DeckEditor"
import Profile from "./user/Profile"
import NotFoundComponent from "./NotFoundComponent"

const routes = [{
        path: '*',
        component: NotFoundComponent,
    },
    {
        path: "/",
        component: Decks,
        name: "home",
        meta: {
            hideNavigation: true
        }
    },
    {
        path: "/login",
        component: Login,
        name: "login",
        meta: {
            visitor: true
        }
    },
    {
        path: "/register",
        component: Register,
        name: "register",
        meta: {
            visitor: true
        }
    },
    {
        path: "/logout",
        component: Logout,
        name: "logout",
        meta: {
            authenticated: true
        }
    },
    {
        path: '/user/:username',
        component: Profile,
        name: "profile",
    },
    // {
    //     path: "/profile/edit",
    //     component: Profile,
    //     name: "profile",
    //     meta: {
    //         authenticated: true
    //     }
    // },
    {
        path: "/deck/editor",
        component: DeckEditor,
        name: "deck-editor",
    },
    {
        path: "/deck/:slug",
        component: Deck,
        name: "deck",
    },
];

const router = new VueRouter({
    mode: 'history',
    routes // short for `routes: routes`
});

export default router;
