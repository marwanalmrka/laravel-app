/*!
 * Jodit Editor (https://xdsoft.net/jodit/)
 * Released under MIT see LICENSE.txt in the project root for license information.
 * Copyright (c) 2013-2025 Valeriy Chupurnov. All rights reserved. https://xdsoft.net
 */
/**
 * @module plugins/backspace
 */
import type { IJodit } from "../../../types/index";
/**
 * Check if two separate elements can be connected
 * @private
 */
export declare function checkJoinNeighbors(jodit: IJodit, fakeNode: Node, backspace: boolean): boolean;
