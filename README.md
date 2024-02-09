[![mindtwo GmbH](https://www.mindtwo.de/downloads/doodles/github/repository-header.png)](https://www.mindtwo.de/)

# Custom Valet/Herd Drivers: `mindtwo/valet-drivers`

## Overview

The `mindtwo/valet-drivers` package provides a seamless way to install and manage a suite of custom valet drivers tailored for use within our agency's MacOS environments. These drivers facilitate the development of projects by ensuring compatibility and efficient integration with Laravel Valet and Laravel Herd environments. Whether you're setting up a new project or migrating an existing one, this package offers an essential toolkit to streamline your workflow.

## Included Drivers

The package includes the following custom valet drivers:

- `MindtwoAwsBedrockValetDriver.php`: Optimized for AWS Bedrock projects.
- `MindtwoBasicValetDriver.php`: A basic driver for generic PHP applications.
- `MindtwoLaravelWordPressValetDriver.php`: Customized for projects combining Laravel and WordPress.
- `MindtwoTypo3ValetDriver.php`: Tailored for TYPO3 projects.
- `MindtwoVnrLaravelValetDriver.php`: Designed for VNR Laravel applications.
- `MindtwoWordPressValetDriver.php`: Specialized for WordPress projects.

These drivers ensure that your development environment is configured to meet the specific needs of each project type, offering a more streamlined and efficient development process.

## Installation

To install the `mindtwo/valet-drivers` package, you will need to have Composer installed on your system. Run the following command in your terminal:

```shell
composer global require mindtwo/valet-drivers
```

## Usage

After installing the package, you can manage your custom valet drivers using two console commands: `install` and `uninstall`.

### Installing Drivers

To install the custom drivers, use the `install` command. By default, this command installs drivers for Laravel Valet. You can also specify installation for Laravel Herd or provide a custom path.

```shell
mindtwo-valet-drivers install
```

Options:
- `--valet`: Install the drivers for Laravel Valet (default).
- `--herd`: Install the drivers for Laravel Herd.
- `--path=<path>`: Install the drivers to a custom directory.

### Uninstalling Drivers

To uninstall the custom drivers, use the `uninstall` command. Without any options, it will attempt to remove the drivers from both Laravel Valet and Laravel Herd directories.

```shell
mindtwo-valet-drivers uninstall
```

Options:
- `--valet`: Only remove the drivers from the Laravel Valet directory.
- `--herd`: Only remove the drivers from the Laravel Herd directory.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email info@mindtwo.de instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[![Back to the top](https://www.mindtwo.de/downloads/doodles/github/repository-footer.png)](#)
