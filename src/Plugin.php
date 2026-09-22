<?php
namespace FlowMailSMTP;

use FlowMailSMTP\Admin\AdminPage;
use FlowMailSMTP\Mail\MailManager;
use FlowMailSMTP\Mail\PHPMailerConfigurator;
use FlowMailSMTP\Mail\TestMailService;
use FlowMailSMTP\Mail\WordPressMailIntegration;
use FlowMailSMTP\Providers\ProviderManager;
use FlowMailSMTP\Providers\ProviderRegistry;
use FlowMailSMTP\Rest\BootstrapController;
use FlowMailSMTP\Rest\ProviderController;
use FlowMailSMTP\Rest\SettingsController;
use FlowMailSMTP\Rest\TestMailController;
use FlowMailSMTP\Settings\CredentialEncryption;
use FlowMailSMTP\Settings\ProviderSettingsRepository;
use FlowMailSMTP\Settings\SettingsRepository;

final class Plugin {
	public function register(): void {
		( new AdminPage() )->register();
		( new BootstrapController() )->register();
		$settings = new SettingsRepository();
		$registry = ProviderRegistry::defaults();
		do_action( 'mailflow_smtp_register_providers', $registry );
		$provider_settings = new ProviderSettingsRepository( new CredentialEncryption() );
		$providers         = new ProviderManager( $registry, $provider_settings, $settings );
		( new ProviderController( $providers ) )->register();
		( new SettingsController( $settings ) )->register();
		$mail        = new MailManager( $providers );
		$integration = new WordPressMailIntegration( $mail, $settings, new PHPMailerConfigurator() );
		$integration->register();
		( new TestMailController( new TestMailService( $mail, $integration ) ) )->register();
	}
}
