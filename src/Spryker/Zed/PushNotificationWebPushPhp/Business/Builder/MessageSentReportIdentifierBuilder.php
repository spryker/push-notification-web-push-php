<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotificationWebPushPhp\Business\Builder;

use Spryker\Zed\PushNotificationWebPushPhp\Business\WebPush\MessageSentReport;

class MessageSentReportIdentifierBuilder implements MessageSentReportIdentifierBuilderInterface
{
    /**
     * @var string
     */
    protected const IDENTIFIER_TEMPLATE = 'notificationId:%d-subscriptionId:%d';

    public function builderIdentifier(MessageSentReport $messageSentReport): string
    {
        return sprintf(
            static::IDENTIFIER_TEMPLATE,
            $messageSentReport->getPushNotificationIdentifier(),
            $messageSentReport->getPushNotificationSubscriptionIdentifier(),
        );
    }
}
