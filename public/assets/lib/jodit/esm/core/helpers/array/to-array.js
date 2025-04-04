/*!
 * Jodit Editor (https://xdsoft.net/jodit/)
 * Released under MIT see LICENSE.txt in the project root for license information.
 * Copyright (c) 2013-2025 Valeriy Chupurnov. All rights reserved. https://xdsoft.net
 */
/**
 * @module helpers/array
 */
import { isNativeFunction } from "../checker/is-native-function.js";
import { reset } from "../utils/reset.js";
/**
 * Always return Array. It's a safe polyfill for [Array.from](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/from) method
 * In certain scenarios (such as with Joomla Mootools), Array.from may be substituted with a less optimal implementation
 * ```javascript
 * Jodit.modules.Helpers.toArray('123') // ['1', '2', '3']
 * Jodit.modules.Helpers.toArray(['test']) // ['test']
 * Jodit.modules.Helpers.toArray(1) // []
 * ```
 */
export const toArray = function toArray(...args) {
    var _a;
    const func = isNativeFunction(Array.from)
        ? Array.from
        : ((_a = reset('Array.from')) !== null && _a !== void 0 ? _a : Array.from);
    return func.apply(Array, args);
};
