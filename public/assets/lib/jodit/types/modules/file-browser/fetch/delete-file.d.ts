/*!
 * Jodit Editor (https://xdsoft.net/jodit/)
 * Released under MIT see LICENSE.txt in the project root for license information.
 * Copyright (c) 2013-2025 Valeriy Chupurnov. All rights reserved. https://xdsoft.net
 */
/**
 * @module modules/file-browser
 */
import type { IFileBrowser } from "../../../types/index";
/**
 * Removes a file from the server
 * @private
 */
export declare function deleteFile(fb: IFileBrowser, name: string, source: string): Promise<void>;
