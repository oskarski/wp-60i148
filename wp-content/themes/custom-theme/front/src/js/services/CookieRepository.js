export default class CookieRepository {
    static set(name, value, expireDays = 31) {
        const date = new Date();
        date.setTime(date.getTime() + (expireDays * 24 * 60 * 60 * 1000));
        const expires = "expires=" + date.toUTCString();
        document.cookie = name + "=" + JSON.stringify(value) + ";" + expires + ";path=/";
    }

    static get(name) {
        name = name + "=";
        let cookiesArray = document.cookie.split(';');
        let cookieValue = "[]";

        for (let i = 0; i < cookiesArray.length; i++) {
            let cookie = cookiesArray[i];

            while (cookie.charAt(0) === ' ') {
                cookie = cookie.substring(1);
            }

            if (cookie.indexOf(name) === 0) {
                cookieValue = cookie.substring(name.length, cookie.length);
            }
        }

        return JSON.parse(cookieValue);
    }
}