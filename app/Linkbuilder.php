<?php
declare(strict_types=1);

namespace App;

class Linkbuilder
{
	public function checkIfLink()
	{

		$db = new \mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE);
		$db->set_charset('utf8mb4');

		$url = $db->real_escape_string(substr($_SERVER['REQUEST_URI'], 1));

			$escapedSlug = $url;
			$redirectResult = $db->query('SELECT url FROM redirect WHERE slug = "' . $escapedSlug . '"');

			if ($redirectResult && $redirectResult->num_rows > 0) {
				$db->query('UPDATE redirect SET hits = hits + 1 WHERE slug = "' . $escapedSlug . '"');
				$url = $redirectResult->fetch_object()->url;

				header('Location: ' . $url, true, 301);
			} elseif (strstr($url, 'build') == 0)
			{
				$myUrl = $_GET['url'];
				$slug = $this->buildLink();
				if ($db->query('INSERT INTO redirect (slug, url, date, hits) VALUES ("' . $slug . '", "' . $myUrl . '", NOW(), 0)')) {
					header('HTTP/1.1 201 Created');
					echo $slug;
					//$db->query('OPTIMIZE TABLE `redirect`');
				}
			} else {
				//$url = DEFAULT_URL . $_SERVER['REQUEST_URI'];
				// 404
				header("HTTP/1.0 404 Not Found");
			}

			$db->close();


		$attributeValue = htmlspecialchars($url);
	}

	public function buildLink()
	{
		return uniqid();
	}

    public function test(): void
    {
        echo 'Hello, autoloaded world!';
    }
}