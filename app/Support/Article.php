<?php

namespace App\Support;

/**
 * The article in the reader demo.
 */
class Article
{
    public const TITLE = 'Why voice makes apps easier to use';

    public const BYLINE = 'Sample article · 2 minute listen';

    /** Where "Share link" points. */
    public const URL = 'https://vipertecpro.com/blog';

    /** @var list<string> */
    public const PARAGRAPHS = [
        'Most of the time we use our phones with our eyes and thumbs. That works well at a desk, but much of life happens elsewhere: in the kitchen, on a walk, behind the wheel or with a child in one arm.',
        'Voice fills those gaps. Dictation lets someone capture a thought in the time it takes to say it, and it helps anyone who finds small keyboards slow or tiring.',
        'Listening matters just as much. An app that can read its own content aloud turns a long article into something you can take on a commute, and makes the same text easier for people with low vision or reading difficulties.',
        'The best voice features feel like they were always there. A microphone in the place you type, a speaker icon next to the words, and the system share sheet when you want to send something on.',
        'Much of this can now happen on the phone itself. iPhone and Android can recognise and speak many languages on the device, and fall back to their online services for languages they can’t handle locally.',
    ];

    public static function text(): string
    {
        return implode("\n\n", self::PARAGRAPHS);
    }
}
