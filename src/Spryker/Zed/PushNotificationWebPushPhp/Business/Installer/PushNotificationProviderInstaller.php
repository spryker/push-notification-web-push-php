<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotificationWebPushPhp\Business\Installer;

use Generated\Shared\Transfer\PushNotificationProviderCollectionRequestTransfer;
use Generated\Shared\Transfer\PushNotificationProviderConditionsTransfer;
use Generated\Shared\Transfer\PushNotificationProviderCriteriaTransfer;
use Generated\Shared\Transfer\PushNotificationProviderTransfer;
use Spryker\Zed\PushNotificationWebPushPhp\Dependency\Facade\PushNotificationWebPushPhpToPushNotificationFacadeInterface;
use Spryker\Zed\PushNotificationWebPushPhp\PushNotificationWebPushPhpConfig;

class PushNotificationProviderInstaller implements PushNotificationProviderInstallerInterface
{
    /**
     * @var \Spryker\Zed\PushNotificationWebPushPhp\Dependency\Facade\PushNotificationWebPushPhpToPushNotificationFacadeInterface
     */
    protected PushNotificationWebPushPhpToPushNotificationFacadeInterface $pushNotificationFacade;

    public function __construct(PushNotificationWebPushPhpToPushNotificationFacadeInterface $pushNotificationFacade)
    {
        $this->pushNotificationFacade = $pushNotificationFacade;
    }

    public function installWebPushPhpProvider(): void
    {
        $pushNotificationProviderCriteriaTransfer = $this->createPushNotificationProviderCriteriaTransfer();
        $pushNotificationProviderCollectionTransfer = $this->pushNotificationFacade
            ->getPushNotificationProviderCollection($pushNotificationProviderCriteriaTransfer);

        /** @var \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationProviderTransfer> $pushNotificationProviderTransfers */
        $pushNotificationProviderTransfers = $pushNotificationProviderCollectionTransfer->getPushNotificationProviders();
        if ($pushNotificationProviderTransfers->count()) {
            return;
        }

        $pushNotificationProviderCollectionRequestTransfer = $this->createPushNotificationProviderCollectionRequestTransfer();
        $this->pushNotificationFacade->createPushNotificationProviderCollection(
            $pushNotificationProviderCollectionRequestTransfer,
        );
    }

    protected function createPushNotificationProviderCriteriaTransfer(): PushNotificationProviderCriteriaTransfer
    {
        $pushNotificationProviderConditionsTransfer = (new PushNotificationProviderConditionsTransfer())
            ->setNames([PushNotificationWebPushPhpConfig::WEB_PUSH_PHP_PROVIDER_NAME]);

        return (new PushNotificationProviderCriteriaTransfer())
            ->setPushNotificationProviderConditions($pushNotificationProviderConditionsTransfer);
    }

    protected function createPushNotificationProviderCollectionRequestTransfer(): PushNotificationProviderCollectionRequestTransfer
    {
        $pushNotificationProviderTransfer = $this->createPushNotificationProviderTransfer();

        return (new PushNotificationProviderCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addPushNotificationProvider($pushNotificationProviderTransfer);
    }

    protected function createPushNotificationProviderTransfer(): PushNotificationProviderTransfer
    {
        return (new PushNotificationProviderTransfer())
            ->setName(PushNotificationWebPushPhpConfig::WEB_PUSH_PHP_PROVIDER_NAME);
    }
}
