<?php

namespace App\NativeComponents;

use App\NativeComponents\Concerns\ReadsAloud;
use App\NativeComponents\Concerns\Shares;
use App\Support\Recipe;
use Illuminate\View\View;

/**
 * A recipe you can follow with messy hands. Text to Speech reads the steps,
 * and in hands-free mode it moves on to the next step by itself when one
 * finishes. Native Share sends the shopping list.
 */
class CookMode extends DemoScreen
{
    use ReadsAloud;
    use Shares;

    public int $step = 0;

    /** Read every step, one after another. */
    public bool $handsFree = false;

    public function readStep(): void
    {
        $this->readAloud('step-'.$this->step, $this->stepText($this->step));
    }

    public function startHandsFree(): void
    {
        $this->handsFree = true;
        $this->readStep();
    }

    public function stopHandsFree(): void
    {
        $this->handsFree = false;
        $this->stopReading();
        $this->status = 'Stopped.';
    }

    public function next(): void
    {
        $this->goTo($this->step + 1);
    }

    public function previous(): void
    {
        $this->goTo($this->step - 1);
    }

    public function shareShoppingList(): void
    {
        $this->shareText('Shopping list', Recipe::shoppingList());
    }

    public function stepText(int $step): string
    {
        return 'Step '.($step + 1).'. '.(Recipe::STEPS[$step] ?? '');
    }

    protected function afterReading(string $key): void
    {
        if (! $this->handsFree) {
            return;
        }

        if ($this->step < count(Recipe::STEPS) - 1) {
            $this->step++;
            $this->readStep();

            return;
        }

        $this->handsFree = false;
        $this->status = 'That was the last step. Enjoy your meal!';
    }

    private function goTo(int $step): void
    {
        $this->step = max(0, min(count(Recipe::STEPS) - 1, $step));

        if ($this->speaking) {
            $this->readStep();
        }
    }

    public function render(): View
    {
        return view('native.cook-mode');
    }
}
