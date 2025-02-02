# Contentstack SDK implemenation guide: PHP

This is a bare-bones example to connect PHP to Contentstack without an SDK.
This example covers the following items:

- SDK initialization
- live preview setup

> This example has Contentstack Live preview set up with SSR mode turnt on. Which means Contentstack adds query parameters to the URL which we grab in the code and give to the Live Preview SDK intance. Contentstack refreshes the browser on content edit each time.

## How to get started

Before you can run this code, you will need a Contentstack "Stack" to connect to.
Follow the following steps to seed a Stack that this codebase understands.

### Install the CLI

```bash
npm install -g @contentstack/cli
```

### Log in via the CLI

```bash
csdx auth:login
```

### Get your organization UID

In your Contentstack Organization dashboard find `Org admin` and copy your Organization ID (Example: `blt481c598b0d8352d9`).

### Create a new stack

Make sure to replace `<YOUR_ORG_ID>` with your actual Organization ID and run the below.

```bash
csdx cm:stacks:seed --repo "timbenniks/contentstack-implementation-guides-seed" --org "<YOUR_ORG_ID>" -n "Implementation Guide"
```

### Create a new delivery token.

Go to Settings > Tokens and create a delivery token. Select the `preview` scope and turn on `Create preview token`

### Turn on Live Preview

Go to Settings > Live Preview. Click enable and select the `Preview` environment in the drop down. Hit save.

### Run your app

```bash
php -s localhost:3000
```

### See your page in live preview mode

Go to Entries and select the only entry in the list.
In the sidebar, click on the live preview icon.
