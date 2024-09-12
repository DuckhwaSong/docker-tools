FROM ghcr.io/open-webui/open-webui:ollama

# debian 계열 동작!
ENV ENV=/etc/profile
RUN echo "alias ll='ls -alF'" >> /etc/bash.bashrc   

#RUN apt-get -y update
#RUN apt-get -y install vim

#RUN /usr/sbin/nginx -s reload
# /usr/sbin/nginx -version #latest>1.25.2 / stable>1.24.0

# docker run -d -v ollama:/root/.ollama -p 11434:11434 --name ollama ollama/ollama
# docker run -d -p 3000:8080 --gpus=all -v ollama:/root/.ollama -v open-webui:/app/backend/data --name open-webui --restart always ghcr.io/open-webui/open-webui:ollama
# docker run -d --network=host -v open-webui:/app/backend/data -e OLLAMA_BASE_URL=http://127.0.0.1:11434 --name open-webui --restart always ghcr.io/open-webui/open-webui:main
# docker exec -it open-webui /bin/bash

curl http://localhost:11434/api/generate -d '{"model":"llama3","prompt":"why is the sky blue?"}'
curl http://localhost:11434/api/chat -d '{"model":"llama3","messages":[{"role":"user","content":"why is the sky blue?"}]}'

cd /root/.ollama/
echo "FROM Llama-3-Open-Ko-8B-Q5_K_M.gguf

TEMPLATE '''{{- if .System }}
<s>{{ .System }}</s>
{{- end }}
<s>Human:
{{ .Prompt }}</s>
<s>Assistant:
'''

SYSTEM '''A chat between a curious user and an artificial intelligence assistant. The assistant gives helpful, detailed, and polite answers to the user's questions. 모든 대답은 한국어(Korean)으로 대답해줘.'''

PARAMETER temperature 0
PARAMETER num_predict 3000
PARAMETER num_ctx 4096
PARAMETER stop <s>
PARAMETER stop </s>" > Modelfile
wget https://huggingface.co/teddylee777/Llama-3-Open-Ko-8B-gguf/resolve/main/Llama-3-Open-Ko-8B-Q5_K_M.gguf
ollama create llama3-8b-ko -f Modelfile
ollama run llama3-8b-ko

docker exec -it open-webui /bin/bash




llama-3.1-korean-8b-instruct-q4_k_m.gguf
wget https://huggingface.co/HoYangE/Llama-3.1-Korean-8B-Instruct-Q4_K_M-GGUF/resolve/main/llama-3.1-korean-8b-instruct-q4_k_m.gguf?download=true


# RAG 방법
# https://velog.io/@cathx618/Ollama%EC%99%80-LangChain%EC%9C%BC%EB%A1%9C-RAG-%EA%B5%AC%ED%98%84%ED%95%98%EA%B8%B0-with-Python
# >>> Document(page_content='beach till daybreak.', metadata={'language': 'en', 'source': 'https://www.gutenberg.org/files/1727/1727-h/1727-h.htm', 'title': 'The Project Gutenberg eBook of The Odyssey, by Homer'}), 
# >>> Document(page_content='to try and drive him out of his own house.', metadata={'language': 'en', 'source': 'https://www.gutenberg.org/files/1727/1727-h/1727-h.htm', 'title': 'The Project Gutenberg eBook of The Odyssey, by Homer'}), 
# >>> Document(page_content='like a shield on the horizon.', metadata={'language': 'en', 'source': 'https://www.gutenberg.org/files/1727/1727-h/1727-h.htm', 'title': 'The Project Gutenberg eBook of The Odyssey, by Homer'}), 
# >>> Document(page_content='stature and prowess.', metadata={'language': 'en', 'source': 'https://www.gutenberg.org/files/1727/1727-h/1727-h.htm', 'title': 'The Project Gutenberg eBook of The Odyssey, by Homer'})

# API
# curl http://localhost:11434/api/chat -d '{"model":"llama3.1-8b-ko","stream": falseㅐㅔ,"messages":[{"role":"user","content":"하늘은 왜 파란가요?"}]}'
# curl http://localhost:11434/api/generate -d '{"model":"llama3.1-8b-ko","prompt":"하늘은 왜 파란가요?"}'
# curl http://localhost:11434/api/generate -d '{"model":"llama3.1","stream": false,"prompt":"하늘은 왜 파란가요?"}'
# curl http://localhost:11434/api/chat -d '{"model":"llama3.1","messages":[{"role":"user","content":"하늘은 왜 파란가요?"}]}'