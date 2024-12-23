<?php

namespace App\Repositories;

use App\Models\PostLike;
use App\Models\CommentLike;
use App\Repositories\Interfaces\LikesRepositoryInterface;
use PDO;
use Psr\Log\LoggerInterface;

class LikesRepository implements LikesRepositoryInterface
{
    private PDO $db;
    private LoggerInterface $logger;

    public function __construct(PDO $db, LoggerInterface $logger)
    {
        $this->db = $db;

        $this->logger = $logger;
    }

    public function savePostLike(PostLike $like): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO posts_likes (uuid, post_uuid, author_uuid) VALUES (:uuid, :post_uuid, :author_uuid)'
        );
        $stmt->execute([
            'uuid' => $like->uuid,
            'post_uuid' => $like->postId,
            'author_uuid' => $like->authorId,
        ]);

        $this->logger->info('PostLike saved', ['uuid' => $like->uuid]);
    }

    public function getByPostUuid(string $postUuid): array
    {
        $stmt = $this->db->prepare('SELECT * FROM posts_likes WHERE post_uuid = :post_uuid');
        $stmt->execute(['post_uuid' => $postUuid]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function saveCommentLike(CommentLike $like): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO comments_likes (uuid, comment_uuid, author_uuid) VALUES (:uuid, :comment_uuid, :author_uuid)'
        );
        $stmt->execute([
            'uuid' => $like->uuid,
            'comment_uuid' => $like->commentId,
            'author_uuid' => $like->authorId,
        ]);

        $this->logger->info('CommentLike saved', ['uuid' => $like->uuid]);
    }

    public function getByCommentUuid(string $commentUuid): array
    {
        $stmt = $this->db->prepare('SELECT * FROM comments_likes WHERE comment_uuid = :comment_uuid');
        $stmt->execute(['comment_uuid' => $commentUuid]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
