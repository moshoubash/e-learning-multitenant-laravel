<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class YouTubeService
{
    public function extractPlaylistId(string $url): ?string
    {
        $parsed = parse_url($url);
        if (!isset($parsed['query'])) return null;

        parse_str($parsed['query'], $query);
        return $query['list'] ?? null;
    }

    public function fetchPlaylistVideos(string $playlistId): array
    {
        $rssUrl = "https://www.youtube.com/feeds/videos.xml?playlist_id={$playlistId}";
        $response = Http::get($rssUrl);

        if (!$response->ok()) {
            throw new \RuntimeException('Failed to fetch playlist from YouTube.');
        }

        $xml = simplexml_load_string($response->body());
        if (!$xml) {
            throw new \RuntimeException('Failed to parse YouTube playlist feed.');
        }

        $videos = [];
        $ns = $xml->getNamespaces(true);

        foreach ($xml->entry as $entry) {
            $videoId = (string)$entry->children($ns['yt'] ?? 'yt')->videoId;
            $title = (string)$entry->title;

            $videos[] = [
                'title' => $title,
                'video_id' => $videoId,
                'video_url' => "https://www.youtube.com/watch?v={$videoId}",
            ];
        }

        return $videos;
    }

    public function getPlaylistVideosFromUrl(string $url): array
    {
        $playlistId = $this->extractPlaylistId($url);
        if (!$playlistId) {
            throw new \InvalidArgumentException('Invalid YouTube playlist URL. Make sure it contains a valid playlist ID.');
        }

        return $this->fetchPlaylistVideos($playlistId);
    }
}
