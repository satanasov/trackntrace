#phpBB Track'n'Trace

Small implementation of [FingerprintJS 2](https://github.com/Valve/fingerprintjs2) for phpBB forum. It allows to track users with multiple accounts by creating a browser fingerprint that is stored in database on each login.

More info about Browser Fingerprinting could be found on EFF's [Panopticlick](https://panopticlick.eff.org/) site.

FingerprintJS is configured to not use Flash and not take in to account the UserAgent - this should create safer tracking mechanism that will not change with browser update. Be warned that fingerprint hash keys are estimates - this is not fool proof system.

Hope you like it.

##Installation:

1. Clone the repo in $phpbb_root_path/ext/anavaro
2. Go in ACP and activate module.
3. Have fin and profit!