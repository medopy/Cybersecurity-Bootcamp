#include <stdio.h>
#include <stdlib.h>

int main(int argc, char *argv[]) {
    if (argc < 2) {
        printf("Usage: %s <filename>\n", argv[0]);
        return 1;
    }

    FILE *file = fopen(argv[1], "rb");
    if (file == NULL) {
        perror("Error opening file");
        return 1;
    }

    unsigned char buffer[16];
    size_t bytesRead;
    long offset = 0;

    while ((bytesRead = fread(buffer, 1, 16, file)) > 0) {
        printf("%08lX: ", offset);

        for (size_t i = 0; i < 16; i++) {
            if (i < bytesRead) {
                printf("%02X ", buffer[i]);
            } else {
                printf("   ");
            }
        }

        printf("  ");

        for (size_t i = 0; i < bytesRead; i++) {
            if (buffer[i] >= 32 && buffer[i] <= 126) {
                printf("%c", buffer[i]);
            } else {
                printf(".");
            }
        }

        printf("\n");
        offset += 16;
    }

    fclose(file);
    return 0;
}