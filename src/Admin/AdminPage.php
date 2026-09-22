<?php
namespace FlowMailSMTP\Admin;

final class AdminPage {
    /** @var string[] */
    private $hooks = array();

    public function register(): void {
        add_action('admin_menu', array($this, 'menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue'));
    }

    public function menu(): void {
        $this->hooks[] = add_menu_page(__('FlowMail SMTP', 'flowmail-smtp'), __('FlowMail SMTP', 'flowmail-smtp'), 'manage_options', 'flowmail-smtp', array($this, 'render'), 'dashicons-email-alt', 80);
        foreach (array('flowmail-smtp' => __('Dashboard', 'flowmail-smtp'), 'flowmail-smtp-mailers' => __('Providers', 'flowmail-smtp'), 'flowmail-smtp-logs' => __('Mail Logs', 'flowmail-smtp'), 'flowmail-smtp-settings' => __('Settings', 'flowmail-smtp')) as $slug => $label) {
            $this->hooks[] = add_submenu_page('flowmail-smtp', $label, $label, 'manage_options', $slug, array($this, 'render'));
        }
    }

    public function render(): void {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You cannot access FlowMail SMTP.', 'flowmail-smtp'));
        }
        // This read-only query value selects an allowlisted admin view; no form is processed.
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $page = isset($_GET['page']) ? sanitize_key(wp_unslash($_GET['page'])) : 'flowmail-smtp';
        if (!in_array($page, array('flowmail-smtp', 'flowmail-smtp-mailers', 'flowmail-smtp-logs', 'flowmail-smtp-settings'), true)) {
            $page = 'flowmail-smtp';
        }
        echo '<div class="wrap"><div id="flowmail-smtp-app" data-page="' . esc_attr($page) . '"></div></div>';
    }

    public function enqueue(string $hook): void {
        if (!in_array($hook, $this->hooks, true)) {
            return;
        }
        $script = plugin_dir_path(FLOWMAIL_SMTP_FILE) . 'dist/admin.js';
        if (!file_exists($script)) {
            add_action('admin_notices', static function () use ($hook): void {
                $screen = get_current_screen();
                if (!$screen || $screen->id !== $hook) {
                    return;
                }
                echo '<div class="notice notice-warning"><p>' . esc_html__('FlowMail SMTP admin assets are missing. Run pnpm build.', 'flowmail-smtp') . '</p></div>';
            });
            return;
        }
        wp_enqueue_script('flowmail-smtp-admin', plugins_url('dist/admin.js', FLOWMAIL_SMTP_FILE), array(), FLOWMAIL_SMTP_VERSION, true);
        wp_add_inline_script('flowmail-smtp-admin', 'window.FlowMailSMTPConfig = ' . wp_json_encode(array(
            'restUrl' => esc_url_raw(rest_url('flowmail-smtp/v1/')),
            'nonce' => wp_create_nonce('wp_rest'),
        ), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) . ';', 'before');
        $style = plugin_dir_path(FLOWMAIL_SMTP_FILE) . 'dist/admin.css';
        if (file_exists($style)) {
            wp_enqueue_style('flowmail-smtp-admin', plugins_url('dist/admin.css', FLOWMAIL_SMTP_FILE), array(), FLOWMAIL_SMTP_VERSION);
        }
    }
}
