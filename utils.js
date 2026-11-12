/**
 * Устанавливает стили для DOM-элемента и возвращает актуальную карту стилей.
 *
 * @param {HTMLElement} dom - Целевой DOM-элемент
 * @param {Object} [styles={}] - Объект со стилями (поддерживает camelCase и kebab-case)
 * @returns {Object} Объект с итоговыми стилями элемента
 */
const CSS = (dom, styles = {}) => {
    if (!dom || !(dom instanceof HTMLElement)) {
        return {};
    }

    // Применяем переданные стили к элементу
    Object.entries(styles).forEach(([prop, value]) => {
        // Преобразуем camelCase (backgroundColor) в kebab-case (background-color) для setProperty
        const cssProp = prop.replace(/[A-Z]/g, match => `-${match.toLowerCase()}`);

        if (value === null || value === '') {
            // Удаляем свойство, если передан null или пустая строка
            dom.style.removeProperty(cssProp);
        } else if (value !== undefined) {
            dom.style.setProperty(cssProp, String(value));
        }
    });

    // Собираем новую карту всех активных инлайн-стилей элемента
    const result = {};
    for (let i = 0; i < dom.style.length; i++) {
        const propName = dom.style[i];
        // Преобразуем обратно в camelCase для удобного доступа в JS
        const camelName = propName.replace(/-([a-z])/g, (_, letter) => letter.toUpperCase());
        result[camelName] = dom.style.getPropertyValue(propName);
    }

    return result;
};

/**
 * Находит первый DOM-элемент по селектору.
 *
 * @param {string} selector - CSS-селектор (".class", "#id", "tag" и др.)
 * @param {HTMLElement|Document|null} [parent=null] - Родительский элемент для поиска
 * @returns {HTMLElement|null} Найденный элемент или null
 */
const DOM = (selector, parent = null) => {
    // Проверяем, что селектор — непустая строка
    if (typeof selector !== 'string' || !selector.trim()) {
        return null;
    }

    // Определяем область поиска (родительский элемент или весь документ)
    const root = parent instanceof Element || parent instanceof Document ? parent : document;

    // Возвращаем первый найденный элемент или null
    return root.querySelector(selector);
};


class DOM_CLASS {
    /**
     * Вспомогательный метод: очищает имена классов от ведущих точек и лишних пробелов.
     * Поддерживает передачу строк вида ".btn .btn-primary" или массива аргументов.
     */
    static _normalize(names) {
        return names
            .flat()
            .filter(name => typeof name === 'string' && name.trim())
            .flatMap(name => name.trim().split(/\s+/))
            .map(name => name.replace(/^\.+/, ''))
            .filter(Boolean);
    }

    /**
     * Возвращает массив имен классов элемента.
     * @param {Element} dom
     * @returns {string[]}
     */
    static list(dom) {
        if (!(dom instanceof Element)) return [];
        return Array.from(dom.classList);
    }

    /**
     * Добавляет один или несколько классов элементу.
     * @param {Element} dom
     * @param {...string} names
     */
    static add(dom, ...names) {
        if (!(dom instanceof Element)) return;
        const cleanNames = this._normalize(names);
        if (cleanNames.length) {
            dom.classList.add(...cleanNames);
        }
    }

    /**
     * Удаляет один или несколько классов у элемента.
     * @param {Element} dom
     * @param {...string} names
     */
    static remove(dom, ...names) {
        if (!(dom instanceof Element)) return;
        const cleanNames = this._normalize(names);
        if (cleanNames.length) {
            dom.classList.remove(...cleanNames);
        }
    }

    /**
     * Проверяет наличие класса у элемента.
     * @param {Element} dom
     * @param {string} name
     * @returns {boolean}
     */
    static have(dom, name) {
        if (!(dom instanceof Element)) return false;
        const [cleanName] = this._normalize([name]);
        return cleanName ? dom.classList.contains(cleanName) : false;
    }

    /**
     * Переключает класс: добавляет, если его нет, и удаляет, если он есть.
     * @param {Element} dom
     * @param {string} name
     * @returns {boolean} Возвращает true, если класс в итоге добавлен, и false — если удален
     */
    static toggle(dom, name) {
        if (!(dom instanceof Element)) return false;
        const [cleanName] = this._normalize([name]);
        return cleanName ? dom.classList.toggle(cleanName) : false;
    }

    static clear(dom){
        if (!(dom instanceof Element)) return false;
        dom.className = '';
    }

    static replace(dom,...names){
        if (!(dom instanceof Element)) return false;
        this.clear(dom);
        this.add(dom,...names);
    }
}
