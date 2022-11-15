<span wire:poll.5s>
    ({{ $accounts->count() }})
    <form>
        <button wire:click="sync()">Sync</button>
        <input wire:model='numberToFetch' type="text" size="3">
    </form>
</span>