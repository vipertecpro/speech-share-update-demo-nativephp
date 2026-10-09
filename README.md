# Speech, Share and Update Demo for NativePHP

An independent demo app by [vipertecpro](https://vipertecpro.com): six example apps and four playgrounds that show four PARqore plugins for NativePHP Mobile in the places people expect them: a microphone in a chat box, a speaker next to a reply, the system share sheet, and an update screen.

Everything is written in PHP with NativePHP Mobile's native screens, and runs on iPhone and Android.

> **Disclosure:** PARqore provided the plugin licences I used to build this demo. This demo is my own work: it is not an official PARqore repository, and it is not made or endorsed by NativePHP.

## The plugins it uses

This repository contains **none of the plugins' code**. To run the demo you need a licence for each of the four plugins, available on the NativePHP Marketplace:

| Plugin | What it does | Get it |
|---|---|---|
| PARqore Speech to Text | Turns speech into text, with live results while the user is speaking | [NativePHP Marketplace](https://nativephp.com/plugins/partek/speech-to-text) |
| PARqore Text to Speech | Reads text aloud in the phone's own voice, with optional language, rate and pitch | [NativePHP Marketplace](https://nativephp.com/plugins/partek/text-to-speech) |
| PARqore Native Share | Opens the system share sheet for text, links and files, and reports the app the user picked | [NativePHP Marketplace](https://nativephp.com/plugins/partek/native-share) |
| PARqore Force Update | Compares the installed version with the minimum and latest you set, and opens the store listing | [NativePHP Marketplace](https://nativephp.com/plugins/partek/force-update) |

After you buy them, your NativePHP dashboard shows how to give Composer access to them.

No other plugins are needed. Composer also installs two free packages: [NativePHP Mobile](https://nativephp.com) (4.6 or later) and NativePHP Mobile UI (0.8), both MIT licensed.

## What's inside

### Example apps

| Demo | From the plugins | Built in this demo |
|---|---|---|
| **AI Assistant** | Speech to Text fills the message box as you speak. Text to Speech reads replies aloud. Native Share sends a reply or the whole chat. | The chat, its sample replies (there is no AI model here), and voice mode sending your message when you stop speaking. |
| **Voice Notes** | Speech to Text turns speech into the note's text. Text to Speech reads it back. Native Share sends it and reports the app that was picked. | Saving notes, adding new dictation to the end, and names like "Gmail" worked out from the app id the plugin reports. |
| **Cook Mode** | Text to Speech reads each step and reports when it finishes. Native Share sends the shopping list. | Moving to the next step when one finishes, and pause on Android (see below). |
| **Language Practice** | Text to Speech says the phrase in a voice for its language. Speech to Text listens in that language and returns the words it heard. | The % match, which compares the words the phone heard with the phrase. It doesn't judge pronunciation. |
| **Field Report** | Speech to Text fills each field. Text to Speech reads the report back. Native Share sends it as a file. | The form, writing the report to a text file, and putting a field back when you cancel dictation. |
| **Article Reader** | Text to Speech reads at the speed you choose and reports when each part finishes. Native Share sends the link. | Reading one paragraph at a time and highlighting it. |

The home screen also shows a "Version 1.1.0 is available" banner. Force Update reports the versions; the banner is this demo's own check against the latest version you configure.

Each screen ends with a short "How this demo works" card that says the same thing, so the app itself is clear about what comes from the plugins.

### Playgrounds

One screen per plugin, with every option it offers and a live log of each call the app makes and each event the plugin sends back, with timings:

- **Speech to Text:** recognition language, live results on or off, stop and cancel.
- **Text to Speech:** voice language, rate and pitch (or the phone's own), pause, resume, stop and `isSpeaking()`.
- **Native Share:** text, a link and a file, and sending straight to WhatsApp on Android.
- **Force Update:** the live `status()`, opening the store listing, and a switch that makes the next launch show the update screen.

## Requirements

- PHP 8.3 or later and Composer
- For iPhone: a Mac with Xcode and an iOS 18 or later simulator or device
- For Android: Android Studio with an Android 8.0 (API 26) or later emulator or device

The minimums come from the plugins: Speech to Text, Text to Speech and Force Update need iOS 18 and Android 8.0. Native Share alone works from iOS 15 and Android 5.0.

## Permissions

Only Speech to Text asks for permissions. The plugin adds them to the app for you, and the phone asks the user the first time they tap a microphone.

| Platform | Permission | Prompt the user sees |
|---|---|---|
| Android | Microphone (`RECORD_AUDIO`) | The standard "Allow … to record audio?" dialog |
| iOS | Speech recognition (`NSSpeechRecognitionUsageDescription`) | "This app converts your speech to text for dictation." |
| iOS | Microphone (`NSMicrophoneUsageDescription`) | "This app uses the microphone to capture speech for dictation." |

Text to Speech, Native Share and Force Update need no permissions.

Speech recognition runs on the phone where the language supports it. Otherwise iOS can use Apple's online recognition and Android its online recognizer, so mention speech in your app's privacy policy.

## Getting started

1. Buy the four plugins on the NativePHP Marketplace and give Composer access as your dashboard describes.
2. Clone this repository and copy the environment file:

   ```bash
   git clone https://github.com/vipertecpro/speech-share-update-demo-nativephp.git
   cd speech-share-update-demo-nativephp
   cp .env.example .env
   ```

3. In `.env`, set `NATIVEPHP_APP_ID` to your own reverse-domain id, such as `com.yourcompany.plugindemos`.
4. Install the dependencies and the NativePHP resources:

   ```bash
   composer install
   php artisan key:generate
   php artisan native:install
   ```

5. Run it on an emulator, simulator or connected phone:

   ```bash
   php artisan native:run android
   ```

   Or `php artisan native:run ios`. The four plugins are already registered in `app/Providers/NativeServiceProvider.php`.

If CocoaPods stops with an encoding error during the iOS build, run `export LANG=en_US.UTF-8` in that terminal and try again.

## Good to know

- **Use a real phone for dictation.** Emulators and simulators often have no working microphone or speech recognizer, so Speech to Text is best tried on a device.
- **Voices.** The demos use the phone's own voice, speed and pitch. Only Language Practice picks a voice for its language, and the Text to Speech playground sends a rate and pitch once you choose to set them. Simulators often include only basic voices, so speech sounds more natural on a phone.
- **Pausing speech.** iOS pauses mid-sentence. Android's speech engine has no pause, so on Android this demo stops the current step or paragraph and starts it again on Resume.
- **Trying Force Update.** `.env.example` sets the app version to 1.0.0, and the demo configures a minimum of 1.0.0 and a latest of 1.1.0, so you see the "update available" banner. Turn on "Pretend this build is too old" in the Force Update playground and reopen the app to see the update screen. In your own app, the minimum and latest would usually come from your API.
- **Seeing your changes.** If a change to the PHP code doesn't show up after `native:run`, uninstall the app from the emulator or simulator and run it again.

## Where things are

| Path | What it holds |
|---|---|
| `app/NativeComponents` | One class per screen |
| `app/NativeComponents/Concerns` | `Dictates`, `ReadsAloud` and `Shares`: the plugin calls and events, shared by every screen |
| `app/NativeComponents/Playgrounds` | The four playgrounds |
| `app/Support` | Sample content: replies, the recipe, phrases and the article |
| `app/Providers/AppServiceProvider.php` | The Force Update configuration |
| `resources/views/native` | The screens' layouts |
| `routes/mobile.php` | The screens' addresses |

## Tests

With the plugins installed, run:

```bash
php artisan test
```

The tests drive every screen with the plugins' own test helpers, so they run without a device.

## Licence

The code in this repository is released under the [MIT licence](LICENSE). The four PARqore plugins are commercial software, licensed separately by PARqore through the NativePHP Marketplace.
