<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotificationWebPushPhp\Business\Creator;

use Generated\Shared\Transfer\ErrorTransfer;

interface ErrorCreatorInterface
{
    public function createErrorTransfer(string $entityIdentifier, string $message): ErrorTransfer;
}
