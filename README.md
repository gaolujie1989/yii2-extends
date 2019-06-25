# yii2-data-staging

#### Introduction
Third part data staging
Download all needed data from third part data, 
no logic processing with own system, 
but record main data and storage compressed serialize text.

Because get third part data is always rate limited, connection limited and slow network delay.
Many times, multi modules need same data for different usage, and processing data with error fail and need re-get data.

#Main Features
Support multi systems
Support multi accounts for each systems
Support different config for each system type data
Support scheduling get data
Support get increment data and condition data for recheck
Support Event when data get success

