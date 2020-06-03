<?php
// IP Blocker Observer
// Adds "Block This IP" link into Admin Whos-Online page

class zcObserverWhosOnlineIpBlockerLink extends base
{
    public function __construct()
    {
        if (function_exists('ip_blocker_is_enabled') && ip_blocker_is_enabled()) {
            $this->attach($this, ['ADMIN_WHOSONLINE_IP_LINKS']);
            require DIR_FS_CATALOG . 'includes/init_includes/init_ip_blocker.php';
        }
    }

    /**
     * @param string $eventID name of the observer event fired
     * @param string $item whos_online record
     * @param string $additional_ipaddress_links updateable list of links
     * @param array $whois_url url of whois service to use for inquiring about the ip address
     */
    protected function update(&$class, $eventID, $item, &$additional_ipaddress_links, &$whois_url)
    {
        if (empty($item)) return;

        if (function_exists('ip_blocker_block') && ip_blocker_block($item['ip_address'])) {
            $link = '[' . IP_BLOCKER_TEXT_IS_BLOCKED . ']';
        } else {
            $link = '<a href="' . zen_href_link(FILENAME_WHOS_ONLINE, zen_get_all_get_params(array('ip', 'action')) . 'action=block&ip=' . $item['ip_address']) . '">' . IP_BLOCKER_TEXT_BLOCK_IP . '</a>';
        }

        $additional_ipaddress_links .= ' &mdash; ' . $link;
    }
}
