<?php

/**
 * Fetch a limited number of events from the database
 * @param PDO $pdo Database connection
 * @param int $limit Number of events to fetch
 * @return array Array of events
 */
function getEvents($pdo, $limit = null)
{
	if ($limit !== null) {
		$stmt = $pdo->prepare("SELECT * FROM events ORDER BY date ASC LIMIT :limit");
		$stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
	} else {
		$stmt = $pdo->prepare("SELECT * FROM events ORDER BY date ASC");
	}
	$stmt->execute();
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Fetch all categories from the database
 * @param PDO $pdo Database connection
 * @return array Array of categories
 */
function getCategories($pdo)
{
	$stmt = $pdo->query("SELECT * FROM categories");
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Count events in a specific category
 * @param PDO $pdo Database connection
 * @param int $category_id Category ID
 * @return int Number of events
 */
function getEventCountByCategory($pdo, $category_id)
{
	$stmt = $pdo->prepare("SELECT COUNT(*) FROM events WHERE category_id = ?");
	$stmt->execute([$category_id]);
	return $stmt->fetchColumn();
}
