@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-soft-border focus:border-primary focus:ring-primary rounded-lg shadow-sm text-on-surface']) }}>
