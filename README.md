# importr

[![Build Status](https://travis-ci.org/sirdiego/importr.svg?branch=master)](https://travis-ci.org/sirdiego/importr) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/sirdiego/importr/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/sirdiego/importr/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/sirdiego/importr/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/sirdiego/importr/?branch=master)


## Installation

After installing the extension via one of the possible methods you need to add the Scheduler Task to your tasks.
* Extbase CommandController Task (extbase) - importr:import:initializeservicemanager

The frequency should be as often as possible (e.g. `* * * * *`), this way the Importr can be as precise as possible with the import timings.
 
TODO: Screenshot

### TYPO3 Composer Mode
`composer require diego/importr`
### Via Extension Manager
TODO: Screenshot
## Usage

After installing the extension and adding the Scheduler Task you can start configuring your possible imports. These are called _Strategies_ and should be placed on the root of your TYPO3 installation.
 