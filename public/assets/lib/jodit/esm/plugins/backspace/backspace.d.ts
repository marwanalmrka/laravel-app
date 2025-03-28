/*!
 * Jodit Editor (https://xdsoft.net/jodit/)
 * Released under MIT see LICENSE.txt in the project root for license information.
 * Copyright (c) 2013-2025 Valeriy Chupurnov. All rights reserved. https://xdsoft.net
 */
/**
 * [[include:plugins/backspace/README.md]]
 * @packageDocumentation
 * @module plugins/backspace
 */
import type { IJodit } from "../../types/index";
import { Plugin } from "../../core/plugin/index";
import "./config";
export declare class backspace extends Plugin {
    static requires: string[];
    protected afterInit(jodit: IJodit): void;
    protected beforeDestruct(jodit: IJodit): void;
    /**
     * Listener BackSpace or Delete button
     */
    private onDelete;
    /**
     * Remove node and replace cursor position out of it
     */
    private safeRemoveEmptyNode;
}
