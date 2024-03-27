export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string;
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: User;
    };
};

export type Message = {
    fromUserId: number;
    toUserId: number;
    text: string;
    time: string;
};

export type Contact = {
    userId: number;
    name: string;
    path: string;
    time: string;
    preview: string;
    messages: Message[];
    active: boolean;
};
