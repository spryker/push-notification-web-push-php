<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotificationWebPushPhp\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Builder\MessageSentReportIdentifierBuilder;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Builder\MessageSentReportIdentifierBuilderInterface;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Creator\ErrorCreator;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Creator\ErrorCreatorInterface;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Creator\WebPushQueueCreator;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Creator\WebPushQueueCreatorInterface;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Expander\PushNotificationSubscriptionDeliveryLogExpander;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Expander\PushNotificationSubscriptionDeliveryLogExpanderInterface;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Filter\PushNotificationFilter;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Filter\PushNotificationFilterInterface;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Installer\PushNotificationProviderInstaller;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Installer\PushNotificationProviderInstallerInterface;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Sender\PushNotificationSender;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Sender\PushNotificationSenderInterface;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Validator\PushNotificationPayloadLengthValidator;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Validator\PushNotificationPayloadLengthValidatorInterface;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Validator\PushNotificationSubscriptionPayloadStructureValidator;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Validator\PushNotificationSubscriptionPayloadStructureValidatorInterface;
use Spryker\Zed\PushNotificationWebPushPhp\Dependency\External\PushNotificationWebPushPhpToSubscriptionInterface;
use Spryker\Zed\PushNotificationWebPushPhp\Dependency\External\PushNotificationWebPushPhpToWebPushInterface;
use Spryker\Zed\PushNotificationWebPushPhp\Dependency\Facade\PushNotificationWebPushPhpToPushNotificationFacadeInterface;
use Spryker\Zed\PushNotificationWebPushPhp\Dependency\Service\PushNotificationWebPushPhpToUtilEncodingServiceInterface;
use Spryker\Zed\PushNotificationWebPushPhp\PushNotificationWebPushPhpDependencyProvider;

/**
 * @method \Spryker\Zed\PushNotificationWebPushPhp\PushNotificationWebPushPhpConfig getConfig()
 */
class PushNotificationWebPushPhpBusinessFactory extends AbstractBusinessFactory
{
    public function createPushNotificationSubscriptionPayloadStructureValidator(): PushNotificationSubscriptionPayloadStructureValidatorInterface
    {
        return new PushNotificationSubscriptionPayloadStructureValidator(
            $this->createErrorCreator(),
        );
    }

    public function createPushNotificationPayloadLengthValidator(): PushNotificationPayloadLengthValidatorInterface
    {
        return new PushNotificationPayloadLengthValidator(
            $this->getUtilEncodingService(),
            $this->createErrorCreator(),
            $this->getConfig(),
        );
    }

    public function createPushNotificationCollectionSender(): PushNotificationSenderInterface
    {
        return new PushNotificationSender(
            $this->createPushNotificationFilter(),
            $this->createErrorCreator(),
            $this->createWebPushQueueCreator(),
            $this->createPushNotificationSubscriptionDeliveryLogExpander(),
            $this->createMessageSentReportIdentifierBuilder(),
        );
    }

    public function createPushNotificationProviderInstaller(): PushNotificationProviderInstallerInterface
    {
        return new PushNotificationProviderInstaller(
            $this->getPushNotificationFacade(),
        );
    }

    public function createPushNotificationFilter(): PushNotificationFilterInterface
    {
        return new PushNotificationFilter();
    }

    public function createErrorCreator(): ErrorCreatorInterface
    {
        return new ErrorCreator();
    }

    public function getWebPushSubscription(): PushNotificationWebPushPhpToSubscriptionInterface
    {
        return $this->getProvidedDependency(PushNotificationWebPushPhpDependencyProvider::WEB_PUSH_SUBSCRIPTION);
    }

    public function createWebPushQueueCreator(): WebPushQueueCreatorInterface
    {
        return new WebPushQueueCreator(
            $this->getWebPushSubscription(),
            $this->getWebPushNotificator(),
            $this->getUtilEncodingService(),
        );
    }

    public function createPushNotificationSubscriptionDeliveryLogExpander(): PushNotificationSubscriptionDeliveryLogExpanderInterface
    {
        return new PushNotificationSubscriptionDeliveryLogExpander();
    }

    public function createMessageSentReportIdentifierBuilder(): MessageSentReportIdentifierBuilderInterface
    {
        return new MessageSentReportIdentifierBuilder();
    }

    public function getWebPushNotificator(): PushNotificationWebPushPhpToWebPushInterface
    {
        return $this->getProvidedDependency(PushNotificationWebPushPhpDependencyProvider::WEB_PUSH_NOTIFICATOR);
    }

    public function getUtilEncodingService(): PushNotificationWebPushPhpToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(PushNotificationWebPushPhpDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    public function getPushNotificationFacade(): PushNotificationWebPushPhpToPushNotificationFacadeInterface
    {
        return $this->getProvidedDependency(PushNotificationWebPushPhpDependencyProvider::FACADE_PUSH_NOTIFICATION);
    }
}
