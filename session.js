class Session {

    constructor() {
        this.enable = false;
        this.token = false;

        this.config = {
            autorizeScript: 'login.php',
            storageTokenVarName: 'jwt',
        }

        this._prevEvent = 'logout';
        this._events = {
            'autorize': [],
            'logout': [],
        }

    }

    async autorize({
        username, password, token,
    }) {

        if ((username && password) || token) {
            const response = await fetch(script, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    ...username ? { username } : {},
                    ...password ? { password } : {},
                    ...token ? { token } : {}
                })
            });

            if (response.ok) {
                if (result.success) {
                    this.token = result.token;
                    // Сохраняем токен в sessionStorage (работает пока открыта вкладка)
                    this._saveToken(result.token);
                    this._do('autorize');
                } else {
                    this.logout();
                }
            }
      
        } else {
            this.logout();
            throw new Error("need login and pass or token");
        }
        return true
    }

    logout() {
        this.token = false;
        this._do('logout')
    }

    async request(url, data = null, method = 'POST') {

        if (!this.enable()) {
            throw new Error("no autorize");
        };

        const options = {
            method: method,
            headers: {
                'Authorization': `Bearer ${this.token}`
            }
        };

        if (method === 'POST' && data) {
            options.headers['Content-Type'] = 'application/json';
            options.body = JSON.stringify(data);
        }

        try {
            const res = await fetch(url, options);
            if (res.status === 401) {

            }
            const out = await res.json();

            return out;

            // if (res.status === 401) {
            //     sessionStorage.removeItem(tokenKey);
            //     setTimeout(checkAuth, 1500);
            // }
        } catch (err) {
            // errorMsg.textContent = "Ошибка сети или сервера";
            // errorMsg.classList.remove('hidden');
        }
    }

    enable() {
        return !!this.token;
    }

    on(eventName, callback) {
        this._events[eventName].push(callback);
        return () => {
            this._events[eventName] = this._events[eventName].filter(item => item !== callback)
        }
    }
    _do(event, ...params) {
        if (this._prevEvent === event) return;
        this._prevEvent = event
        this._events[event].map(callback => {
            try {
                callback(...params);
            } catch (error) {
                console.log(error);
            }
        })
    }
    _saveToken(token) {
        sessionStorage.setItem(this.config.storageTokenVarName, token);
    }
    _loadToken() {
        return sessionStorage.getItem(this.config.storageTokenVarName);
    }
    _clearToken() {
        sessionStorage.removeItem(this.config.storageTokenVarName);
    }

}
let session = new Session();
export { session }