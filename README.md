# Magento 2 Session Reaper Patch for CVE-2025-54236

**Patch for CVE-2025-54236(a.k.a Session Reaper) which allows customer account takeover and RCE under certain conditions. This patch is actually a Magento 2 extension and universal compatible for Magento 2.3 & 2.4. If you cannot upgrade Magento or cannot apply the official hotfix, try this one.**

## Background

### CVSS score

**9.1 CRITICAL**

### Official information

 - [Published on 2025-09-09](https://helpx.adobe.com/security/products/magento/apsb25-88.html)
 - [Hotfix](https://experienceleague.adobe.com/en/docs/experience-cloud-kcs/kbarticles/ka-27397)

### What can the attacker damage your store?

 - Customer account takeover
 - RCE under certain conditions

## Feature

 - Fixes CVE-2025-54236(a.k.a Session Reaper) vulnerability

#### Compatibility

*No `preference` is used, so your Magento is still upgradable.*

#### Behavior difference

*The official fix still allows dangerous parameter to go to `Setter`s, this patch does not allow it.*

## Requirements

Magento/Adobe Commerce 2.3 or 2.4

## Installation

```bash 
composer require wubinworks/module-session-reaper-patch
```

## ♥

If you like this extension or this extension helped you, please _**share**_ and _**★star☆**_ [this repository](https://github.com/wubinworks/magento2-session-reaper-patch), it's not hard!

### You may also like these extensions

#### Security

 - [Magento 2 Cosmic Sting Patch for CVE-2024-34102](https://github.com/wubinworks/magento2-cosmic-sting-patch "Magento 2 Cosmic Sting Patch for CVE-2024-34102")
 - [Magento 2 Trojan Orders Patch for CVE-2022-24086, CVE-2022-24087](https://github.com/wubinworks/magento2-template-filter-patch "Magento 2 Trojan Orders Patch for CVE-2022-24086, CVE-2022-24087")
 - [Magento 2 Enhanced XML Security](https://github.com/wubinworks/magento2-enhanced-xml-security "Magento 2 Enhanced XML Security")
 - [Magento 2 Encryption Key Manager CLI](https://github.com/wubinworks/magento2-encryption-key-manager-cli "Magento 2 Encryption Key Manager CLI")
 - [Magento 2 JWT Authentication Patch](https://github.com/wubinworks/magento2-jwt-auth-patch "Magento 2 JWT Authentication Patch")

#### Feature

 - [Magento 2 Free Sitemap Based Cache Warmer Extension](https://github.com/wubinworks/magento2-free-cache-warmer "Magento 2 Free Sitemap Based Cache Warmer Extension")
 - [Magento 2 Disable Customer Extension](https://github.com/wubinworks/magento2-disable-customer "Magento 2 Disable Customer Extension")
 - [Magento 2 Disable Customer Change Email Extension](https://github.com/wubinworks/disable-change-email "Magento 2 Disable Customer Change Email Extension")
 - [Magento 2 Price Formatter Extension](https://github.com/wubinworks/magento2-price-formatter "Magento 2 Price Formatter Extension")
