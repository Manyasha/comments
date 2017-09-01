<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
     /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $pdo  = DB::connection()->getPdo();
        $stmt = $pdo->query('select * from comments order by parent_id , id ');
        $comments = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $this->buildTree($comments);
    }

    public function create(Request $request) {
        if ( empty($request->message) ) {
            return response()->json(['error' => 'Message is empty'], 404);
        }
        $commentId = DB::table('comments')->insertGetId([
            'text' => $request->message,
            'parent_id' => $request->parentId,
            'created_at' => new \DateTime()
        ]);
        $newComment = DB::table('comments')->where('id', $commentId)->get();
        return $newComment;
    }

    public function edit(Request $request) {
        DB::table('comments')
            ->where('id', $request->id)
            ->update([
                'text' => $request->message,
                'updated_at' => new \DateTime()
            ]);
        $updatedComment = DB::table('comments')->where('id', $request->id)->get();
        if ( !count($updatedComment) ) {
            return response()->json(['error' => 'Comment not found'], 404);
        }
        return $updatedComment;
    }

    public function remove(Request $request) {
        DB::table('comments')
            ->where('id', $request->id)
            ->update([
                'is_deleted' => 1
            ]);
        return response()->json(['success' => 'Comment mark as deleted'], 200);
    }

    private function buildTree(&$data, $rootId = null) {
        $tree = array();
        foreach ($data as $id => $node) {
            if ($node['parent_id'] === $rootId) {
                unset($data[$id]);
                $node['childs'] = $this->buildTree($data, $node['id']);
                $tree[] = $node;
            }
        }
        return $tree;
    }
}
