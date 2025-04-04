/*!
 * Jodit Editor (https://xdsoft.net/jodit/)
 * Released under MIT see LICENSE.txt in the project root for license information.
 * Copyright (c) 2013-2025 Valeriy Chupurnov. All rights reserved. https://xdsoft.net
 */
/**
 * @module plugin
 */
import type { IJodit, Nullable, PluginInstance, PluginType } from "../../../types/index";
/**
 * Create instance of plugin
 * @private
 */
export declare function makeInstance(jodit: IJodit, plugin: PluginType): Nullable<PluginInstance>;
