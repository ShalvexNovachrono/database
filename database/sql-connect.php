<?php

    $db = new PDO('sqlite:../database/db.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $res = $db->exec(
        "CREATE TABLE IF NOT EXISTS `users` (
        id TEXT NOT NULL,
        username TEXT NOT NULL,
        key_id TEXT NOT NULL,
        token_id TEXT NOT NULL
        )"
    );

    $res = $db->exec(
        "CREATE TABLE IF NOT EXISTS `tokens` (
        token_id TEXT NOT NULL,
        website_url TEXT NOT NULL,
        refid TEXT NOT NULL,
        send_id TEXT NOT NULL,
        working TEXT NOT NULL,
        date_made TEXT NOT NULL
        )"
    );

    $res = $db->exec(
        "CREATE TABLE IF NOT EXISTS `container` (
        user_id TEXT NOT NULL,
        container_id TEXT NOT NULL,
        container_name TEXT NOT NULL,
        date_made TEXT NOT NULL
        )"
    );

    $res = $db->exec(
        "CREATE TABLE IF NOT EXISTS `capsule` (
        user_id TEXT NOT NULL,
        container_id TEXT NOT NULL,
        capsule_data TEXT NOT NULL
        )"
    );

    $res = $db->exec(
        "CREATE TABLE IF NOT EXISTS `quickLogin` (
        user_id TEXT NOT NULL,
        quickLogin_code TEXT NOT NULL,
        date_made TIME NOT NULL
        )"
    );

    $res = $db->exec(
        "CREATE TABLE IF NOT EXISTS `qrcode` (
        code TEXT NOT NULL,
        user_id TEXT NOT NULL,
        username TEXT NOT NULL,
        token_id TEXT NOT NULL,
        approved TEXT NOT NULL,
        expire_time TIME NOT NULL
        )"
    );

    $res = $db->exec(
        "CREATE TABLE IF NOT EXISTS `extraCapsule` (
        userID TEXT NOT NULL,
        extraCapsuleID TEXT NOT NULL,
        indexNumber INT NOT NULL,
        extraCapsuleData TEXT NOT NULL,
        extraCapsuleDateTime TEXT NOT NULL,
        extraCapsuleUpdateDateTime TEXT NOT NULL
        )"
    );

    