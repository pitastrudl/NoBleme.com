/*! ******************************************************************************************************************/
/*!                                                                                                                  */
/*!                                        DUMP OF THE DATABASE'S STRUCTURE                                          */
/*!                                                                                                                  */
/*! ******************************************************************************************************************/
/*!                                                                                                                  */
/*! Below (commented) is a personal reminder of the options I use locally in PHPMyAdmin to generate this dump,       */
/*! I only leave this as a reminder, feel free to ignore it :)                                                       */
/*!                                                                                                                  */
/*! Export method: Custom                                                                                            */
/*! Uncheck data (export structure only)                                                                             */
/*! View output as text (do not save output to a file)                                                               */
/*! Uncheck the displaying of comments                                                                               */
/*! Check every statement except one: uncheck the auto_increment value                                               */
/*! Syntax to use: neither of the above (insert into tbl_name values (1,2,3))                                        */
/*!                                                                                                                  */
/*! ******************************************************************************************************************/

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
CREATE DATABASE IF NOT EXISTS `nobleme` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `nobleme`;

DROP TABLE IF EXISTS `dev_blogs`;
CREATE TABLE IF NOT EXISTS `dev_blogs` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `is_deleted` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `posted_at` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `title_en` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_fr` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body_en` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `body_fr` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_deleted` (`is_deleted`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `dev_tasks`;
CREATE TABLE IF NOT EXISTS `dev_tasks` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fk_users` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `is_deleted` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `finished_at` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `admin_validation` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `is_public` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `priority_level` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `title_en` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_fr` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `body_en` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `body_fr` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `fk_dev_tasks_categories` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `fk_dev_tasks_milestones` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `source_code_link` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_author` (`fk_users`),
  KEY `index_category` (`fk_dev_tasks_categories`),
  KEY `index_milestone` (`fk_dev_tasks_milestones`),
  KEY `index_deleted` (`is_deleted`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `dev_tasks_categories`;
CREATE TABLE IF NOT EXISTS `dev_tasks_categories` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title_en` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_fr` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `dev_tasks_milestones`;
CREATE TABLE IF NOT EXISTS `dev_tasks_milestones` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sorting_order` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `title_en` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_fr` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `summary_en` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `summary_fr` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_sorting_order` (`sorting_order`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `internet_admin_notes`;
CREATE TABLE IF NOT EXISTS `internet_admin_notes` (
  `global_notes` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `draft_en` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `draft_fr` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `snippets` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `template_en` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `template_fr` longtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `internet_categories`;
CREATE TABLE IF NOT EXISTS `internet_categories` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `display_order` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `name_en` varchar(510) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_fr` varchar(510) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description_en` mediumtext COLLATE utf8mb4_unicode_ci,
  `description_fr` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_display_order` (`display_order`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `internet_eras`;
CREATE TABLE IF NOT EXISTS `internet_eras` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `began_in_year` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `ended_in_year` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `name_en` varchar(510) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_fr` varchar(510) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description_en` mediumtext COLLATE utf8mb4_unicode_ci,
  `description_fr` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `internet_images`;
CREATE TABLE IF NOT EXISTS `internet_images` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `is_deleted` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `uploaded_at` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `file_name` varchar(510) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tags` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_nsfw` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `used_in_pages_fr` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `used_in_pages_en` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_deleted` (`is_deleted`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `internet_pages`;
CREATE TABLE IF NOT EXISTS `internet_pages` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fk_internet_eras` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `is_deleted` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `is_dictionary_entry` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `is_cultural_entry` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `title_en` varchar(510) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_fr` varchar(510) COLLATE utf8mb4_unicode_ci NOT NULL,
  `redirection_en` varchar(510) COLLATE utf8mb4_unicode_ci NOT NULL,
  `redirection_fr` varchar(510) COLLATE utf8mb4_unicode_ci NOT NULL,
  `appeared_in_year` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `appeared_in_month` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `spread_in_year` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `spread_in_month` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `is_nsfw` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `is_gross` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `is_political` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `is_politically_incorrect` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `definition_en` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `definition_fr` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `private_admin_notes` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_era` (`fk_internet_eras`),
  KEY `index_dictionary` (`is_dictionary_entry`),
  KEY `index_cultural` (`is_cultural_entry`),
  KEY `index_appeared` (`appeared_in_year`,`appeared_in_month`),
  KEY `index_spread` (`spread_in_year`,`spread_in_month`),
  KEY `index_deleted` (`is_deleted`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `internet_pages_categories`;
CREATE TABLE IF NOT EXISTS `internet_pages_categories` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fk_internet_pages` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `fk_internet_categories` int(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `index_page` (`fk_internet_pages`),
  KEY `index_category` (`fk_internet_categories`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `irc_channels`;
CREATE TABLE IF NOT EXISTS `irc_channels` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(153) COLLATE utf8mb4_unicode_ci NOT NULL,
  `languages` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_order` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `details_en` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `details_fr` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_display_order` (`display_order`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `logs_activity`;
CREATE TABLE IF NOT EXISTS `logs_activity` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fk_users` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `is_deleted` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `happened_at` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `is_administrators_only` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `language` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activity_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `activity_type` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activity_amount` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `activity_summary_en` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `activity_summary_fr` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `activity_nickname` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activity_moderator_nickname` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `moderation_reason` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_related_user` (`fk_users`),
  KEY `index_language` (`language`),
  KEY `index_related_foreign_key` (`activity_id`),
  KEY `index_activity_type` (`activity_type`),
  KEY `index_deleted` (`is_deleted`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `logs_activity_details`;
CREATE TABLE IF NOT EXISTS `logs_activity_details` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fk_logs_activity` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `content_description_en` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `content_description_fr` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `content_before` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `content_after` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_logs_activity` (`fk_logs_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `logs_scheduler`;
CREATE TABLE IF NOT EXISTS `logs_scheduler` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `happened_at` int(10) UNSIGNED NOT NULL,
  `task_id` int(10) UNSIGNED NOT NULL,
  `task_type` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `task_description_en` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `task_description_fr` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_happened_at` (`happened_at`),
  KEY `index_related_foreign_key` (`task_id`),
  KEY `index_task_type` (`task_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `meetups`;
CREATE TABLE IF NOT EXISTS `meetups` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `is_deleted` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `event_date` date NOT NULL DEFAULT '0000-00-00',
  `location` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_reason_en` varchar(105) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_reason_fr` varchar(105) COLLATE utf8mb4_unicode_ci NOT NULL,
  `details_en` longtext COLLATE utf8mb4_unicode_ci,
  `details_fr` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_deleted` (`is_deleted`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `meetups_people`;
CREATE TABLE IF NOT EXISTS `meetups_people` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fk_meetups` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `fk_users` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `nickname` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `attendance_confirmed` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `extra_information_en` varchar(510) COLLATE utf8mb4_unicode_ci NOT NULL,
  `extra_information_fr` varchar(510) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_meetup` (`fk_meetups`),
  KEY `index_people` (`fk_users`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `quotes`;
CREATE TABLE IF NOT EXISTS `quotes` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fk_users_submitter` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `is_deleted` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `admin_validation` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `submitted_at` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `is_nsfw` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `language` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_submitter` (`fk_users_submitter`),
  KEY `index_deleted` (`is_deleted`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `quotes_users`;
CREATE TABLE IF NOT EXISTS `quotes_users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fk_quotes` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `fk_users` int(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `index_quote` (`fk_quotes`),
  KEY `index_user` (`fk_users`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `stats_pageviews`;
CREATE TABLE IF NOT EXISTS `stats_pageviews` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `page_name_en` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `page_name_fr` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `page_url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `view_count` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `view_count_archive` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `last_viewed_at` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_view_count` (`view_count`,`view_count_archive`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `system_scheduler`;
CREATE TABLE IF NOT EXISTS `system_scheduler` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `planned_at` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `task_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `task_type` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `task_description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_task_id` (`task_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `system_variables`;
CREATE TABLE IF NOT EXISTS `system_variables` (
  `update_in_progress` int(10) UNSIGNED NOT NULL,
  `latest_query_id` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `last_scheduler_execution` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `last_pageview_check` int(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`update_in_progress`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `system_versions`;
CREATE TABLE IF NOT EXISTS `system_versions` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `version` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `build` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_full_version` (`version`,`build`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `is_deleted` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `nickname` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_administrator` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `is_global_moderator` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `is_moderator` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `moderator_rights` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `moderator_title_en` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `moderator_title_fr` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_visited_at` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `last_action_at` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `last_visited_page_en` varchar(510) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_visited_page_fr` varchar(510) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_visited_url` varchar(510) COLLATE utf8mb4_unicode_ci NOT NULL,
  `current_ip_address` varchar(135) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0.0.0.0',
  `is_banned_until` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `is_banned_because` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_access_rights` (`is_administrator`,`is_global_moderator`,`is_moderator`,`moderator_rights`(127)),
  KEY `index_doppelganger` (`current_ip_address`),
  KEY `index_banned` (`is_banned_until`),
  KEY `index_deleted` (`is_deleted`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `users_guests`;
CREATE TABLE IF NOT EXISTS `users_guests` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(135) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0.0.0.0',
  `randomly_assigned_name_en` varchar(510) COLLATE utf8mb4_unicode_ci NOT NULL,
  `randomly_assigned_name_fr` varchar(510) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_visited_at` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `last_visited_page_en` varchar(510) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_visited_page_fr` varchar(510) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_visited_url` varchar(510) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `users_login_attempts`;
CREATE TABLE IF NOT EXISTS `users_login_attempts` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fk_users` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `ip_address` varchar(135) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0.0.0.0',
  `attempted_at` int(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `index_user` (`fk_users`),
  KEY `index_guest` (`ip_address`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `users_private_messages`;
CREATE TABLE IF NOT EXISTS `users_private_messages` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fk_users_recipient` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `fk_users_sender` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `is_deleted` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `sent_at` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `read_at` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_inbox` (`fk_users_recipient`),
  KEY `index_outbox` (`fk_users_sender`),
  KEY `index_deleted` (`is_deleted`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `users_profile`;
CREATE TABLE IF NOT EXISTS `users_profile` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fk_users` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `email_address` varchar(510) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `birthday` date NOT NULL DEFAULT '0000-00-00',
  `spoken_languages` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` varchar(105) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lives_at` varchar(105) COLLATE utf8mb4_unicode_ci NOT NULL,
  `occupation` varchar(105) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_text` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_user` (`fk_users`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `users_settings`;
CREATE TABLE IF NOT EXISTS `users_settings` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fk_users` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `show_nsfw_content` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `hide_tweets` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `hide_youtube` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `hide_google_trends` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `hide_from_activity` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `index_user` (`fk_users`),
  KEY `index_nsfw_filter` (`show_nsfw_content`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `users_stats`;
CREATE TABLE IF NOT EXISTS `users_stats` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fk_users` int(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `index_user` (`fk_users`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `writings_contests`;
CREATE TABLE IF NOT EXISTS `writings_contests` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fk_users_winner` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `fk_writings_texts_winner` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `is_deleted` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `language` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `started_at` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `ended_at` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `nb_entries` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `contest_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `contest_topic` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_winner` (`fk_users_winner`),
  KEY `index_winning_text` (`fk_writings_texts_winner`),
  KEY `index_language` (`language`),
  KEY `index_deleted` (`is_deleted`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `writings_contests_votes`;
CREATE TABLE IF NOT EXISTS `writings_contests_votes` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fk_writings_contests` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `fk_writings_texts` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `fk_users` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `vote_weight` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `index_author` (`fk_users`),
  KEY `index_contest` (`fk_writings_contests`),
  KEY `index_text` (`fk_writings_texts`),
  KEY `index_votes` (`vote_weight`,`fk_writings_contests`,`fk_writings_texts`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `writings_texts`;
CREATE TABLE IF NOT EXISTS `writings_texts` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fk_users` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `fk_writings_contests` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `is_deleted` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `language` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_anonymous` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `edited_at` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `character_count` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_author` (`fk_users`,`is_anonymous`),
  KEY `index_contest` (`fk_writings_contests`),
  KEY `index_language` (`language`),
  KEY `index_deleted` (`is_deleted`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


ALTER TABLE `dev_tasks` ADD FULLTEXT KEY `index_title_en` (`title_en`);
ALTER TABLE `dev_tasks` ADD FULLTEXT KEY `index_title_fr` (`title_fr`);

ALTER TABLE `internet_images` ADD FULLTEXT KEY `index_file_name` (`file_name`);
ALTER TABLE `internet_images` ADD FULLTEXT KEY `index_tags` (`tags`);

ALTER TABLE `internet_pages` ADD FULLTEXT KEY `index_title_en` (`title_en`,`redirection_en`);
ALTER TABLE `internet_pages` ADD FULLTEXT KEY `index_title_fr` (`title_fr`,`redirection_fr`);
ALTER TABLE `internet_pages` ADD FULLTEXT KEY `index_contents_en` (`definition_en`);
ALTER TABLE `internet_pages` ADD FULLTEXT KEY `index_contents_fr` (`definition_fr`);