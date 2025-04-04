/*!
 * Jodit Editor (https://xdsoft.net/jodit/)
 * Released under MIT see LICENSE.txt in the project root for license information.
 * Copyright (c) 2013-2025 Valeriy Chupurnov. All rights reserved. https://xdsoft.net
 */
/**
 * @module modules/uploader
 */
import type { HandlerError, HandlerSuccess, IUploader } from "../../../types/index";
export declare function processOldBrowserDrag(self: IUploader, cData: DataTransfer | null, handlerSuccess?: HandlerSuccess, handlerError?: HandlerError, onFinally?: () => void): void;
