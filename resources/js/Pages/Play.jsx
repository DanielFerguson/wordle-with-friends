import { useState } from "react";
import { Inertia } from "@inertiajs/inertia";

const blankWord = "     ";

function classNames(...classes) {
    return classes.filter(Boolean).join(" ");
}

const LetterBox = ({ letter = "A" }) => {
    return (
        <div className="h-16 w-full rounded bg-gray-200 flex items-center justify-center text-2xl font-bold">
            {letter.toUpperCase()}
        </div>
    );
};

const Guess = ({ word }) => {
    return (
        <>
            {Array.from(word).map((letter) => (
                <LetterBox key={letter} letter={letter} />
            ))}
        </>
    );
};

const Play = () => {
    const [guesses, setGuesses] = useState(["adieu", "adieu", "adieu"]);
    const [values, setValues] = useState({
        first_name: "",
        last_name: "",
        email: "",
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
        Inertia.post("/users", values);
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
                    return guesses && guesses[index] ? (
                        <Guess key={index} word={guesses[index]} />
                    ) : (
                        <Guess key={index} word={blankWord} />
                    );
                })}
            </main>

            <form className="flex gap-2 w-full">
                <input
                    type="text"
                    name="guess"
                    maxLength="5"
                    className="flex-grow rounded"
                />
                <button>Guess</button>
            </form>
        </div>
    );
};

export default Play;
