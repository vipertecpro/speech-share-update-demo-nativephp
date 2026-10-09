<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * Sample replies for the assistant demo, so it works offline. A real app
 * sends the message to its AI model here; the microphone, read-aloud and
 * share buttons around it stay the same.
 */
class Assistant
{
    /**
     * Prompts offered on the empty chat, with their sample replies.
     *
     * @var array<string, string>
     */
    public const SUGGESTIONS = [
        'Plan a weekend in Jaipur' => "Here's a relaxed weekend in Jaipur.\n\nSaturday: start early at Amber Fort before the crowds, then lunch in the old city. Walk through Johari Bazaar in the afternoon and catch the sunset from Nahargarh Fort.\n\nSunday: see Hawa Mahal and the City Palace in the morning, then Jantar Mantar next door. Finish with dinner at a rooftop café facing the old walls.\n\nTip: October to March is the most comfortable time to go.",
        'Write a polite follow-up email' => "Subject: Following up on our conversation\n\nHi Priya,\n\nI hope your week is going well. I wanted to follow up on the proposal I sent last Tuesday and see whether you had any questions.\n\nI'm happy to walk through it on a short call if that's easier. Would Thursday or Friday afternoon work for you?\n\nThanks for your time,\nSam",
        'Explain recursion like I’m five' => "Imagine you're standing in a line and want to know your place in it. You ask the person in front of you, \"What number are you?\" They don't know either, so they ask the person in front of them, and so on.\n\nThe first person in line knows they're number one. They tell the person behind them, who adds one, and the answer travels back down the line until it reaches you.\n\nThat's recursion: a problem that asks a smaller copy of itself, until one copy is small enough to just know the answer.",
    ];

    public static function reply(string $prompt): string
    {
        foreach (self::SUGGESTIONS as $suggestion => $reply) {
            if (self::normalise($prompt) === self::normalise($suggestion)) {
                return $reply;
            }
        }

        return "This is where your AI model's answer appears. The demo uses sample replies so it works offline, and the three suggestions show longer ones.\n\nEverything around the answer is real: you can dictate your message with the microphone, have replies read aloud, and share any reply to another app.";
    }

    /**
     * Case, punctuation and accents don't matter, so a dictated request
     * ("write a polite follow up email") matches its suggestion.
     */
    private static function normalise(string $text): string
    {
        return Str::of(Str::ascii($text))->lower()->replaceMatches('/[^a-z0-9]+/', ' ')->squish()->toString();
    }
}
