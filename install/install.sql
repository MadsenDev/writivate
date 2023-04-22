SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `guides` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `creator_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `content` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `guide_tags` (
  `id` int(11) NOT NULL,
  `guide_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `guide_translations` (
  `id` int(11) NOT NULL,
  `guide_id` int(11) NOT NULL,
  `language` varchar(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `guide_updates` (
  `id` int(11) NOT NULL,
  `guide_id` int(11) DEFAULT NULL,
  `updater_id` int(11) DEFAULT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `guide_views` (
  `id` int(11) NOT NULL,
  `guide_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `view_time` datetime NOT NULL,
  `duration` decimal(10,6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `languages` (
  `id` int(11) UNSIGNED NOT NULL,
  `language` varchar(255) NOT NULL,
  `language_code` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `password_reset_tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `ranks` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `can_create_guide` tinyint(1) NOT NULL DEFAULT 0,
  `can_edit_guide` tinyint(1) NOT NULL DEFAULT 0,
  `can_delete_guide` tinyint(1) NOT NULL DEFAULT 0,
  `can_manage_categories` tinyint(1) NOT NULL DEFAULT 0,
  `can_manage_users` tinyint(1) NOT NULL DEFAULT 0,
  `can_manage_ranks` tinyint(1) NOT NULL DEFAULT 0,
  `can_manage_views` tinyint(1) NOT NULL DEFAULT 0,
  `can_manage_system_settings` tinyint(1) NOT NULL DEFAULT 0,
  `can_add_translations` tinyint(1) NOT NULL DEFAULT 0,
  `can_delete_translations` tinyint(1) NOT NULL DEFAULT 0,
  `can_edit_translations` tinyint(1) NOT NULL DEFAULT 0,
  `can_manage_language` tinyint(1) NOT NULL DEFAULT 0,
  `can_manage_suggestions` tinyint(1) NOT NULL DEFAULT 0,
  `can_change_theme` tinyint(1) NOT NULL DEFAULT 0,
  `can_add_theme` tinyint(1) NOT NULL DEFAULT 0,
  `can_delete_theme` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings` (`id`, `name`, `value`) VALUES
(1, 'site_name', 'My Site'),
(2, 'logo_url', '/public/images/logo.png'),
(3, 'default_category_id', '1'),
(4, 'comments_moderation', '1'),
(5, 'site_description', 'My Site Description'),
(6, 'contact_email', 'contact@email.com'),
(7, 'primary_color', '#000000'),
(8, 'secondary_color', '#000000'),
(9, 'footer_text', 'My Site Footer text'),
(11, 'content_type_plural', 'Posts'),
(13, 'content_type_single', 'Post'),
(15, 'registration_enabled', '1'),
(16, 'theme', 'default.css'),
(17, 'show_views', '1'),
(18, 'enable_suggestions', '1');

CREATE TABLE `suggestions` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `suggestion` text NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `themes` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `themes` (`id`, `title`, `filename`) VALUES
(1, 'Default', 'default.css'),
(2, 'Dark Mode', 'darkmode.css');

CREATE TABLE `theme_options` (
  `id` int(11) NOT NULL,
  `label` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `group_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `theme_options` (`id`, `label`, `type`, `name`, `group_name`) VALUES
(1, 'Body Text Color', 'color', 'body_text_color', 'body'),
(2, 'Body Background Color', 'color', 'body_bg_color', 'body'),
(3, 'Link Color', 'color', 'a_color', 'links'),
(4, 'Link Hover Color', 'color', 'a_hover_color', 'links'),
(5, 'Aside Background Color', 'color', 'aside_bg_color', 'links'),
(6, 'Aside Link Color', 'color', 'aside_a_color', 'links'),
(7, 'Aside Link Hover Background Color', 'color', 'aside_a_hover_bg_color', 'links'),
(8, 'Content Background Color', 'color', 'content_bg_color', 'content'),
(9, 'Recently Viewed Guides Border Width', 'text', 'recently_viewed_guides_border_width', 'content'),
(10, 'Recently Viewed Guides Border Color', 'color', 'recently_viewed_guides_border_color', 'content'),
(11, 'Recently Viewed Guides List Item Border Bottom Width', 'text', 'recently_viewed_guides_li_border_bottom_width', 'content'),
(12, 'Recently Viewed Guides List Item Border Bottom Color', 'color', 'recently_viewed_guides_li_border_bottom_color', 'content'),
(13, 'Recently Viewed Guides Span Color', 'color', 'recently_viewed_guides_span_color', 'content'),
(14, 'Markdown Body Pre Background Color', 'color', 'markdown_body_pre_bg_color', 'markdown'),
(15, 'Markdown Body Pre Border Width', 'text', 'markdown_body_pre_border_width', 'markdown'),
(16, 'Markdown Body Pre Border Color', 'color', 'markdown_body_pre_border_color', 'markdown'),
(17, 'Updates List Border Width', 'text', 'updates_list_border_width', 'updates_list'),
(18, 'Updates List Border Color', 'color', 'updates_list_border_color', 'updates_list'),
(19, 'Search Form Border Width', 'text', 'search_form_border_width', 'search_form'),
(20, 'Search Form Border Color', 'color', 'search_form_border_color', 'search_form'),
(21, 'Search Form Box Shadow', 'text', 'search_form_box_shadow', 'search_form'),
(22, 'Search Form Input Text Color', 'color', 'search_form_input_text_color', 'search_form'),
(23, 'Search Form Input Background Color', 'color', 'search_form_input_bg_color', 'search_form'),
(24, 'Search Form Button Background Color', 'color', 'search_form_button_bg_color', 'search_form'),
(25, 'Search Form Button Text Color', 'color', 'search_form_button_text_color', 'search_form'),
(26, 'Search Form Button Hover Background Color', 'color', 'search_form_button_hover_bg_color', 'search_form'),
(27, 'Form Control Border Width', 'text', 'form_control_border_width', 'form_control'),
(28, 'Form Control Border Color', 'color', 'form_control_border_color', 'form_control'),
(29, 'Primary Button Background Color', 'color', 'btn_primary_bg_color', 'buttons'),
(30, 'Primary Button Text Color', 'color', 'btn_primary_text_color', 'buttons'),
(31, 'Primary Button Hover Background Color', 'color', 'btn_primary_hover_bg_color', 'buttons'),
(32, 'Profile Rank Color', 'color', 'profile_rank_color', 'profile'),
(33, 'Tag Background Color', 'color', 'tag_bg_color', 'tags'),
(34, 'Tag Hover Background Color', 'color', 'tag_hover_bg_color', 'tags'),
(35, 'Language Selection Background Color', 'color', 'language_selection_bg_color', 'language_selection'),
(36, 'Language Selection Border Width', 'text', 'language_selection_border_width', 'language_selection'),
(37, 'Language Selection Border Color', 'color', 'language_selection_border_color', 'language_selection'),
(38, 'Language Selection Select Border Width', 'text', 'language_selection_select_border_width', 'language_selection'),
(39, 'Language Selection Select Border Color', 'color', 'language_selection_select_border_color', 'language_selection'),
(40, 'Header Background Color', 'color', 'header_bg_color', 'header'),
(41, 'Header Text Color', 'color', 'header_text_color', 'header'),
(42, 'Nav Link Color', 'color', 'nav_a_color', 'navigation'),
(43, 'Nav Link Hover Background Color', 'color', 'nav_a_hover_bg_color', 'navigation'),
(44, 'Nav Submenu Background Color', 'color', 'nav_submenu_bg_color', 'navigation'),
(45, 'Nav Submenu Box Shadow', 'text', 'nav_submenu_box_shadow', 'navigation'),
(46, 'Nav Submenu List Item Link Hover Background Color', 'color', 'nav_submenu_li_a_hover_bg_color', 'navigation'),
(47, 'Footer Text Color', 'color', 'footer_text_color', 'footer');

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `rank_id` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

ALTER TABLE `guides`
  ADD PRIMARY KEY (`id`),
  ADD KEY `creator_id` (`creator_id`),
  ADD KEY `category_id` (`category_id`);

ALTER TABLE `guide_tags`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `guide_translations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `guide_id` (`guide_id`);

ALTER TABLE `guide_updates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `guide_id` (`guide_id`),
  ADD KEY `updater_id` (`updater_id`);

ALTER TABLE `guide_views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `guide_id` (`guide_id`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `language_code` (`language_code`);

ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `ranks`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `suggestions`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `themes`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `theme_options`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `rank_id` (`rank_id`);

ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `guides`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `guide_tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `guide_translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `guide_updates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `guide_views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `languages`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `password_reset_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `ranks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `suggestions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `themes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `theme_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`);

ALTER TABLE `guides`
  ADD CONSTRAINT `guides_ibfk_1` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `guides_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

ALTER TABLE `guide_translations`
  ADD CONSTRAINT `fk_guide_translations_guide_id` FOREIGN KEY (`guide_id`) REFERENCES `guides` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `guide_updates`
  ADD CONSTRAINT `guide_updates_ibfk_1` FOREIGN KEY (`guide_id`) REFERENCES `guides` (`id`),
  ADD CONSTRAINT `guide_updates_ibfk_2` FOREIGN KEY (`updater_id`) REFERENCES `users` (`id`);

ALTER TABLE `guide_views`
  ADD CONSTRAINT `guide_views_ibfk_1` FOREIGN KEY (`guide_id`) REFERENCES `guides` (`id`),
  ADD CONSTRAINT `guide_views_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `password_reset_tokens`
  ADD CONSTRAINT `password_reset_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`rank_id`) REFERENCES `ranks` (`id`);
COMMIT;