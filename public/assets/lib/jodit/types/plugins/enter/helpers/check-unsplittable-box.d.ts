/*!
 * Jodit Editor (https://xdsoft.net/jodit/)
 * Released under MIT see LICENSE.txt in the project root for license information.
 * Copyright (c) 2013-2025 Valeriy Chupurnov. All rights reserved. https://xdsoft.net
 */
/**
 * @module plugins/enter
 */
import type { IJodit } from "../../../types/index";
/**
 * Inside quote/tables cell, etc. you can't split so just add br
 * @private
 */
export declare function checkUnsplittableBox(fake: Text, jodit: IJodit, currentBox: HTMLElement): boolean;
