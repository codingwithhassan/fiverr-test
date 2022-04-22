# Test

You have been provided with a schema, and some static data.

The test will consist of writing a query on the provided schema.

## Query

The intent of the query is to summarise reviews (and their ratings) over monthly periods, and by their
linked restaurants (which we call branches).

The output rows should be:
- 'Company' - A company name from companies.name

- 'Branch' - A branch name from branches.name

- 'Month' - A formatted date from survey_responses.visit_datetime, in the format 'Month Number'
hyphen 'Year'. E.g. The datetime 2020-03-14 would be formatted as '03-20'.

- 'TripAdvisor Reviews' - A number of TripAdvisor reviews.

- 'TripAdvisor Rating' - An average TripAdvisor rating.

- 'Facebook Reviews' - A number of Facebook reviews.

- 'Facebook Rating' - An average Facebook rating.

- 'Google Reviews' - A number of Google reviews.

- 'Google Rating' - An average Google rating.

- 'Total Reviews' - A total number of reviews.

- 'Average Rating' - An average rating.

The query should provide one row per branch & month combination - the following summary data is required
per row:
- The average & total number of reviews from each review channel (TripAdvisor, Facebook,
Google). The review channel can be identified by survey_responses.social_type_id via a lookup to
social_types.

- The average & total overall number of reviews.

- Order by branch name and month.

Further details:
- Consider only active branches (which represent restaurants). These have status 1 and brand_site
value of 0.

- Consider only active companies. These have status 1.

- Consider all reviews linked to those branches (stored in survey_responses), identified by
survey_mode_id = 8, and with status in (1, 2, 5). The rating left is provided in social_raw_score.

- If a branch has no data for any month, it should be represented by a single row with the company
name set, and the Month column set to 'N/A'.

- The date when the review is left is stored in survey_responses.visit_datetime.

If you get stuck, please try to follow as many of the requirements as possible - an incomplete
answer with notes on how you'd progress is better than no answer at all!

## Schema

```sql
DROP TABLE IF EXISTS `branches`;

CREATE TABLE `branches` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` int(11) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `brand_site` tinyint(1) unsigned DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`),
  KEY `brand_site_status_company` (`brand_site`,`status`,`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `companies`;

CREATE TABLE `companies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `key_name` varchar(255) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `social_types`;

CREATE TABLE `social_types` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `survey_responses`;

CREATE TABLE `survey_responses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` int(10) unsigned NOT NULL,
  `branch_id` int(10) unsigned NOT NULL,
  `hash_code` char(5) NOT NULL,
  `survey_mode_id` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `api_version` tinyint(4) NOT NULL DEFAULT '0',
  `social_type_id` tinyint(1) unsigned DEFAULT NULL,
  `social_review_id` varchar(255) DEFAULT NULL,
  `survey_type_id` int(11) unsigned DEFAULT NULL,
  `visit_datetime` datetime DEFAULT NULL,
  `social_score` decimal(7,3) DEFAULT NULL,
  `social_raw_score` decimal(7,3) DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`),
  KEY `status` (`status`),
  KEY `visit_datetime` (`visit_datetime`),
  KEY `survey_mode_id` (`survey_mode_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
```

## Data

```sql
INSERT INTO `branches` (`id`, `company_id`, `name`, `brand_site`, `status`)
VALUES
    (1, 1, 'Marambi Lounge', 0, 1),
    (2, 1, 'Cabot Lounge', 0, 1),
    (3, 1, 'Ferengi Lounge', 0, 0),
    (4, 1, 'Lounges Brand Page', 1, 1),
    (5, 2, 'Frisco House', 0, 1);

INSERT INTO `companies` (`id`, `name`, `key_name`, `status`)
VALUES
    (1, 'Lounges', 'lounges', 1),
    (2, 'Frisco House of Pancakes', 'friscoshop', 1);

INSERT INTO `social_types` (`id`, `name`)
VALUES
    (1, 'TripAdvisor'),
    (2, 'Facebook'),
    (3, 'Google'),
    (4, 'Twitter'),
    (5, 'Instagram');

INSERT INTO `survey_responses` (`id`, `company_id`, `branch_id`, `hash_code`, `survey_mode_id`, `api_version`, `social_type_id`, `social_review_id`, `survey_type_id`, `visit_datetime`, `social_score`, `social_raw_score`, `status`)
VALUES
    (3634740, 1, 1, '6mbvO', 1, 0, NULL,    NULL,           NULL,   '2019-07-17 20:56:00',    NULL,       NULL,   1),
    (3634865, 1, 2, '6tlg9', 8, 0, 2,       '10111',        1,      '2019-07-17 20:58:00',    100.000,    5.000,  2),
    (3634866, 1, 1, 'dp89j', 8, 0, 2,       '10112',        1,      '2020-01-17 22:30:25',    80.000,     4.000,  2),
    (3634868, 1, 3, 'WPnOV', 8, 0, 2,       '10113',        1,      '2020-01-17 21:05:44',    20.000,     1.000,  2),
    (3634928, 1, 1, 'BWnIe', 1, 0, NULL,    NULL,           NULL,   '2021-07-18 03:53:43',    NULL,       NULL,   1),
    (3634929, 1, 3, 'uCQyG', 1, 0, NULL,    NULL,           NULL,   '2021-07-18 05:55:44',    NULL,       NULL,   1),
    (3634932, 1, 1, 'd39aQ', 8, 0, 3,       'Ax12_999',     1,      '2021-07-18 05:53:44',    80.000,     4.000,  1),
    (3634933, 1, 1, 'fsG3C', 8, 0, 3,       'Ax12_911',     1,      '2021-07-17 19:46:05',    60.000,     3.000,  1),
    (3634936, 1, 4, 'wOqIX', 8, 0, 3,       'Ax12_912',     1,      '2021-07-17 20:09:05',    40.000,     2.000,  1),
    (3636753, 1, 3, 'JNdmA', 8, 0, 1,       '6904',         1,      '2021-09-17 15:52:01',    80.000,     4.000,  2),
    (3638501, 1, 2, '70NFc', 8, 0, 3,       'Ax12_913',     1,      '2021-09-18 15:33:21',    100.000,    5.000,  2),
    (3639636, 1, 1, 'ZuRFo', 1, 0, NULL,    NULL,           1,      '2021-09-18 16:33:21',    NULL,       NULL,   4),
    (3649744, 2, 5, 'CavIB', 1, 0, NULL,    NULL,           1,      '2021-09-18 17:33:21',    NULL,       NULL,   1);
```