{{-- Keep the shell geometry with the layout, including when older compiled assets are cached. --}}
<style>
    @media (min-width: 768px) {
        .admin-shell-header.admin-shell-header {
            box-sizing: border-box;
            height: calc(5rem + var(--safe-top, 0px));
            min-height: calc(5rem + var(--safe-top, 0px));
            max-height: calc(5rem + var(--safe-top, 0px));
            flex-shrink: 0;
            padding-top: var(--safe-top, 0px);
            padding-bottom: 0;
        }
        header.admin-shell-header {
            display: flex;
            align-items: center;
            box-shadow: none;
        }
        header.admin-shell-header > div {
            width: 100%;
        }
    }
</style>
