# ECDP Integration for Magento

## Overview

The **ECDP Integration for Magento** allows you to seamlessly connect your Magento store with the ECDP marketing automation platform. ECDP offers a comprehensive suite of tools to optimize marketing efforts, personalize customer interactions, and drive sales growth.

## Installation & Configuration

1. **Install the Extension**
   - Upload the extension package via Magento's Component Manager or manually via FTP.
   - Run the necessary Magento commands to enable the extension.

2. **Configure General Settings**
   - Navigate to Admin Panel -> Expertsender -> General Settings -> General Configuration.
   - Enter API Key, Tracking Code, and Website ID.
   - Enable or disable API Logging.

3. **Set Up Data Preferences**
   - Choose whether to use the billing or shipping address for customer phone numbers under Data Customers.

4. **Configure Consent Forms**
   - Go to Admin Panel -> Expertsender -> Form Settings.
   - Customize text displayed before consents.
   - Choose between Single Opt-In or Double Opt-In for each form.

5. **Map Attributes and Statuses**
   - Feature Mapping: Admin Panel -> Expertsender -> Feature Mappings -> Create.
     - Map Magento attributes to ECDP features for users, products, or orders.
   - Order Status Mapping: Admin Panel -> Expertsender -> Order Status Mappings -> Create.
     - Map Magento order statuses to ECDP statuses.

6. **Manage Consents**
   - Create Consents: Admin Panel -> Expertsender -> Consents -> Create.
     - Define consents for forms like Registration, Profile Edit, Checkout, and Newsletter.


## Integration Features

- **Easy Configuration**: General settings for API Key, Tracking Codes, and API Logging.
- **Data Synchronization**: Map customer attributes, product features, and order statuses to ECDP.
- **Consent Management**: Single Opt-In (SOI) and Double Opt-In (DOI) options with customizable consent checkboxes.
- **Data Transmission**: Secure data transmission via cron jobs, with a retry mechanism for transmission failures.
  
## ECDP Features & Benefits

- **Enhanced Communication**: Utilize bulk communication channels like Email, SMS, and Web Push to effectively reach your customers.
- **Automated Workflows**: Set up automated workflows triggered by customer actions such as sign-ups, abandoned carts, or order completions.
- **Advanced Segmentation**: Leverage detailed customer data for precise segmentation, enabling targeted marketing campaigns.
- **Personalized On-site Content**: Deliver personalized content using pop-ups, banners, and forms with an intuitive drag-and-drop editor.
- **Powerful Recommendation Engine**: Automatically suggest products based on customer behavior, supporting advanced filters and boosters.
- **Comprehensive Reporting**: Access detailed reports on emails, SMS, and web traffic to inform your marketing strategies.

### Bulk Communication

- **Channels**: 
  - Email
  - SMS
  - Web Push Notifications
- **Automated Workflows**: 
  - **Triggers**: Sign-up, Scheduled, On Date, Custom Event, Abandoned Browse, Abandoned Cart, Add to Cart, Order, Price Drop
- **Templates**: A wide range of pre-built, customizable templates for various marketing automation use cases.
- **Reports**: 
  - Detailed analytics on Email, SMS, and Web Push performance
  - Web Traffic Tracking and Insights
  - Recommendation Engine Report

### On-site Content & Web Traffic Tracking

- **Visitor Tracking**: Track both anonymous and recognized visitors, merging anonymous data with recognized profiles.
- **Form Scraping**: Enhance visitor recognition and subscription rates.
- **Content Tools**: Create pop-ups, banners, and customizable forms using a drag-and-drop editor.

### Customers & Data

- **Segmentation**: Advanced customer segmentation for targeted campaigns.
- **RFM Analysis**: Evaluate customer value based on Recency, Frequency, and Monetary metrics.
- **Discount Codes**: Generate and manage discount codes within campaigns.
- **Recommendation Engine**: Provide personalized product recommendations.
