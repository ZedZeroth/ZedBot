# ZedBot

*Note: The four key entities not represented by Eloquent models are written uppercase and bold. These are: **NETWORK**, **EXCHANGE**, **MARKET**, and **API**. The twelve Eloquent models are written capitalized and bold: e.g. **Customer**, **Trade**, etc.*

## Introduction

ZedBot is an semi-automated currency exchange application. It interacts with currency **MARKET**s, **EXCHANGE** platforms, and payment **NETWORK**s via their **API**s in order to:

1. Calculate exchange **Rate**s.
2. Maintain **Offer**s at competitive **Rate**s.
3. Detect new **Trade**s and communicate with a **Customer**'s exchange **Profile** via **Message**s, to either:
    1. Assist new **Customer**s with onboarding/verification, or
    2. Provide "buyer" **Customer**s with an **Invoice**.
4. It then either:
    1. Detects **Payment**s from a "buyer" **Customer**'s verified **Account** and completes the relevant **Trade**, or
    1. Makes a **Payment** to a "seller" **Customer**'s verified **Account**.
5. *[Is a Receipt model requied?]*
6. **Customer** processing also involves **IdentityDocument** and **RiskAssessment** models.

*Note: The **Currency** model is used throughout the application.*

## Non-model entities (4)

The four key entities not represented by Eloquent models are:

### NETWORK (Payment networks involve *indirect* API interaction)

All **Payment**s and **Account**s exist on a real-world payment **NETWORK** such as the UK banking "FPS" network or the decentralized "Bitcoin" blockchain network.

### EXCHANGE (Exchange platforms involve *direct* API interaction)

All **Profile**s, **Offer**s, **Trade**s, **Message**s, and **Invoice**s exist on a real-world **EXCHANGE** platform such as "LBC" or "ZZR".

### MARKET (Currency markets involve *direct* API interaction)

All **Rate**s exist on a real-world currency **MARKET** such as "GMN" or "BFX".

*Note: Models not tied directly to a **NETWORK**, **EXCHANGE**, or **MARKET** include:*
1. ***Customer**s and their associated **IdentityDocument**s and **RiskAssessments***
3. *The **Currency** model.*

### API services ("API")

All interactions with **NETWORK**s, **EXCHANGE**s, and **MARKET**s are made via **API**s and their respective adapter classes. Note that **NETWORK**s do not have their own **API** and hence are interacted with indirectly (e.g. via the **API**s of banks or blockchain explorers). **EXCHANGE**s and **MARKET**s provide their own **API**s and are interacted with directly.

## Model categorization & relationships (12)

Eloquent models can be grouped into the following five categories.

*Note: Relationships to models in other categories are written in bold.*

### Customer-based models (3)

* **Customer** ("hasMany" IdentityDocuments, RiskAssessments, **Profile**s, and **Account**s)
    * **IdentityDocument** ("belongsTo" 1 Customer)
    * **RiskAssessment** ("belongsTo" 1 Customer)

### Exchange-based models (5)

* **Profile**  ("belongsTo" 1 **Customer**) ("hasMany" Offers, Trades, and Messages)
    * **Offer** ("belongsTo" 1 Profile and 2 **Currency**) ("hasMany" Trades)
    * **Trade** ("belongsTo" 1 Offer and 2 Profiles) ("hasMany" Messages) ("hasOne" Invoice)
    * **Message** ("belongsTo" 1 Trade and 2 Profiles)
    * **Invoice** ("belongsTo" 1 Trade) ("hasMany" **Payment**s)

### Network-based models (2)

* **Account** ("belongsTo" 1 **Customer** and 1 **Currency**) ("hasMany" Payments)
    * **Payment** ("belongsTo" 2 Accounts and 1 **Currency**) ("hasMany" **Invoice**s)

### Market-based models (1)

* **Rate** ("belongsTo" 2 **Currency**)

### The Currency model (1)

* **Currency** ("hasMany" **Rate**s, **Offer**s, **Account**s, and **Payment**s)

## Model identifiers

All models have a unique incrementing integer "id" property used in the Eloquent database as their primary key, and as a foreign key for defining relationships. In addition to this "id" property, each model has other unique identifier properties used for identification either on the internal system, on **NETWORK**s/**EXHANGE**s, or in communication with **Customers**. Such identifiers force the consideration of uniqueness in the real-world in order to avoid potential collisions (more than one real-world entity being representedy the same Eloquent model).

### Customer Identifier

"customer"::customer_id::surname::surname_collision_increment::given_name_1::given_name_2

### IdentityDocument Identifier

"identity_document"::customer_id::type::dob::expiry_date::date_collision_increment

### RiskAssessment Identifier

*No real-world entity.*

### Profile Identifier

"profile"::exchange::username::username_collision_increment

### Offer Identifier

"offer"::exchange::exchange_offer_identifier::exchange_offer_identifier_collision_increment

### Trade Identifier

"trade"::exchange::exchange_trade_identifier::exchange_trade_identifier_collision_increment

### Message Identifier

"message"::exchange::exchange_trade_identifier::timestamp::timestamp_collision_increment::first_n_alphanumeric_characters

### Invoice Identifier

"invoice"::[see Trade]

### Account Identifier

* Banking networks: "account"::network::api::currency::network_identifier (e.g. sort_code::account_number)
* Blockchain networks: "account"::network::api::currency::address
* Exchange networks: "account"::exchange::api::currency::profile::deposit_address

### Account NetworkAccountName

* Banking networks: "Account name"
* Blockchain networks: "Address"
* Exchange networks: ???

### Account Label

* Banking networks: "Banking nickname / assumed account name"
* Blockchain networks: "A useful label"
* Exchange networks: ???

### Payment Identifier

Unique banking network identifiers (e.g. FPS TRN) are not available.
Blockchain network transcation identifiers may not be unique due to cloned networks and multiple currencies/tokens, therefore there is a collision risk.  
Internal exchange "profile to profile" payments may have no identifier system so may need to involve timestamps and deposit addresses.

Proposed:

* Banking networks: "payment"::network::api::currency::api_identifier (e.g. ERN)
* Blockchain networks: "payment"::network::api::currency::tx_id
* Exchange networks: "payment"::exchange::api::currency::timestamp:deposit_address

### Payment Memo

Used for communication with **Customer**s and the link **Payment**s to **Invoice**s.

* Banking networks: "Payment reference"
* Blockchain networks: "TX ID"
* Exchange networks: "Deposit address"

### Currency Code

e.g. GBP, BTC etc.

### Rate Identifier

market::base_currency_code::quote_currency_code::timestamp
