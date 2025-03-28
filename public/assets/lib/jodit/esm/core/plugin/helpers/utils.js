/*!
 * Jodit Editor (https://xdsoft.net/jodit/)
 * Released under MIT see LICENSE.txt in the project root for license information.
 * Copyright (c) 2013-2025 Valeriy Chupurnov. All rights reserved. https://xdsoft.net
 */
/**
 * @module plugin
 */
import { kebabCase } from "../../helpers/string/kebab-case.js";
/**
 * @private
 */
export function normalizeName(name) {
    return kebabCase(name).toLowerCase();
}
