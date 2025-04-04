/*!
 * Jodit Editor (https://xdsoft.net/jodit/)
 * Released under MIT see LICENSE.txt in the project root for license information.
 * Copyright (c) 2013-2025 Valeriy Chupurnov. All rights reserved. https://xdsoft.net
 */
/**
 * [[include:plugins/line-height/README.md]]
 * @packageDocumentation
 * @module plugins/line-height
 */
import type { IJodit } from "../../types/index";
import { Plugin } from "../../core/plugin/index";
import "./config";
export declare class lineHeight extends Plugin {
    buttons: Plugin['buttons'];
    constructor(jodit: IJodit);
    protected afterInit(jodit: IJodit): void;
    private applyLineHeight;
    protected beforeDestruct(jodit: IJodit): void;
}
