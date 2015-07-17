/***************************************************************************
 *                                  _   _ ____  _
 *  Project                     ___| | | |  _ \| |
 *                             / __| | | | |_) | |
 *                            | (__| |_| |  _ <| |___
 *                             \___|\___/|_| \_\_____|
 *
 * Copyright (C) 1998 - 2013, Daniel Stenberg, <daniel@haxx.se>, et al.
 *
 * This software is licensed as described in the file COPYING, which
 * you should have received as part of this distribution. The terms
 * are also available at http://curl.haxx.se/docs/copyright.html.
 *
 * You may opt to use, copy, modify, merge, publish, distribute and/or sell
 * copies of the Software, and permit persons to whom the Software is
 * furnished to do so, under the terms of the COPYING file.
 *
 * This software is distributed on an "AS IS" basis, WITHOUT WARRANTY OF ANY
 * KIND, either express or implied.
 *
 ***************************************************************************/
#include <stdio.h>
#include <curl/curl.h>

void read_user_info(char* content) {
    char* sp;
    FILE *fp;
    if ((fp = fopen("/home/zhangpei/tts/user.info", "r")) != NULL) {
        fgets(content, 100, fp);
    }
}

int main(int argc, char* argv[])
{
  if (argc != 2) {
    printf("usage: %s url\n", argv[0]);
    exit(-1);
  }

  CURL *curl;
  CURLcode res;

  curl = curl_easy_init();
  if(curl) {
    // http://vaquero.corp.youdao.com//image?type=img&product=ead-click&name=nc107&drawname=ead.click.status.proportion&period=hour&cubtype=ead-click_click&width=800&height=600
    curl_easy_setopt(curl, CURLOPT_URL, argv[1]);
    char content[100];
    read_user_info(content);
    curl_easy_setopt(curl, CURLOPT_USERPWD, content);

    /* Perform the request, res will get the return code */
    res = curl_easy_perform(curl);
    /* Check for errors */
    if(res != CURLE_OK)
      fprintf(stderr, "curl_easy_perform() failed: %s\n",
              curl_easy_strerror(res));

    /* always cleanup */
    curl_easy_cleanup(curl);
  }
  return 0;
}
