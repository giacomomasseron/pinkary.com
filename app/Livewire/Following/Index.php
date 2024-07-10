<?php

declare(strict_types=1);

namespace App\Livewire\Following;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

final class Index extends Component
{
    use WithoutUrlPagination, WithPagination;

    /**
     * The component's user ID.
     */
    #[Locked]
    public int $userId;

    /**
     * Indicates if the modal is opened.
     */
    public bool $isOpened = false;

    /**
     * Renders the user's followers.
     */
    public function render(): View
    {
        $user = User::findOrFail($this->userId);

        return view('livewire.following.index', [
            'user' => $user,
            'following' => $this->isOpened ? $user->following()->withExists([
                'following as is_follower' => function (Builder $query): void {
                    $query->where('user_id', auth()->id());
                },
            ])->latest('pivot_following_id')->simplePaginate(10) : collect(),
        ]);
    }
}
