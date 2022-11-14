<span wire:poll.5s>
    ({{ $accounts->count() }})
    <form>
        <button wire:click="sync()">Sync</button>
    </form>
</span>