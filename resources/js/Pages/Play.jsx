import { useState } from "react";
import { Inertia } from "@inertiajs/inertia";

const emptyLetter = { color: "empty", char: " " };
const emptyRow = [
    emptyLetter,
    emptyLetter,
    emptyLetter,
    emptyLetter,
    emptyLetter,
];

function classNames(...classes) {
    return classes.filter(Boolean).join(" ");
}

const LetterBox = ({ letter }) => {
    return (
        <div
            className={classNames(
                "h-16 w-full rounded flex items-center justify-center text-2xl font-bold",
                letter.color == "green" && "bg-green-500",
                letter.color == "yellow" && "bg-yellow-400",
                letter.color == "gray" && "bg-gray-400",
                letter.color == "empty" && "bg-gray-200"
            )}
        >
            {letter.char.toUpperCase()}
        </div>
    );
};

const Guess = ({ word }) => {
    return (
        <>
            {Array.from(word).map((letter, index) => (
                <LetterBox key={index} letter={letter} />
            ))}
        </>
    );
};

const Play = ({ game }) => {
    const [guesses, setGuesses] = useState(
        game.guesses.map((guess) => guess.attempt)
    );
    const [values, setValues] = useState({
        guess: "brash",
    });

    function handleChange(e) {
        const key = e.target.id;
        const value = e.target.value;
        setValues((values) => ({
            ...values,
            [key]: value,
        }));
    }

    function handleSubmit(e) {
        e.preventDefault();
        Inertia.post("/play", values);
    }

    return (
        <div className="pt-6 flex flex-col gap-8 items-center max-w-sm mx-auto">
            <header className="w-full flex items-center justify-between">
                <nav>
                    <a href="/dashboard">Back</a>
                </nav>
            </header>

            <main className="grid grid-cols-5 gap-2 w-full">
                {[...Array(5).keys()].map((index) => {
                    return guesses && guesses.length > index ? (
                        <Guess key={index} word={guesses[index]} />
                    ) : (
                        <Guess key={index} word={emptyRow} />
                    );
                })}
            </main>

            {!game.completed ? (
                <form className="flex gap-2 w-full" onSubmit={handleSubmit}>
                    <input
                        id="guess"
                        value={values.guess}
                        onChange={handleChange}
                        type="text"
                        name="guess"
                        maxLength="5"
                        className="flex-grow rounded"
                    />
                    <button>Guess</button>
                </form>
            ) : (
                <div>Well done! Thank's for playing today.</div>
            )}
        </div>
    );
};

export default Play;
